<?php

namespace App\Observers;

use App\Helpers\Constant;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $statusOrder = array_keys(Constant::STATUS_ORDER);
        $statusBill = array_keys(Constant::STATUS_BILL);
        $newStatus = $order->status;
        if ($newStatus == $statusOrder[2]) {
            $bill = $order->bill()->first();
            if ($bill) {
                $bill->update(['status' => $statusBill[1]]);
            }
        } else {
            $bill = $order->bill()->first();
            if ($bill) {
                $bill->update(['status' => $statusBill[0]]);
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}