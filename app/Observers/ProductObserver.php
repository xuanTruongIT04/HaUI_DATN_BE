<?php

namespace App\Observers;

use App\Helpers\Constant;
use App\Models\Product;
use Carbon\Carbon;

class ProductObserver
{
    /**
     * Handle the Product "retrieved" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function retrieved(Product $product)
    {
        $this->checkExpiryDate($product);
    }

    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        $this->checkExpiryDate($product);
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $this->checkExpiryDate($product);
    }

    /**
     * Handle the Product "saved" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function saved(Product $product)
    {
        $this->checkExpiryDate($product);
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }


    /**
     * Check the expiry date of the product and update the status if necessary.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    protected function checkExpiryDate(Product $product)
    {
        $now = Carbon::now();
        $trashedStatus = Constant::STATUS_PRODUCT['2'];
        if ($product->expiry_date <= $now) {
            $product->status = $trashedStatus;
            $product->saveQuietly();
        }
    }
}
