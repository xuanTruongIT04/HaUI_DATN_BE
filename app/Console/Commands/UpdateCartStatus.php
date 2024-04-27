<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Helpers\Constant;
use App\Models\Cart;
use Carbon\Carbon;

class UpdateCartStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:update-status';


    /** 
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cart status to "expired" if status is "active" and no changes for 30 days.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $listStatusOrder = array_keys(Constant::STATUS_CART);
        $expiredStatus = $listStatusOrder[2]; // Assuming the "expired" status is at index 2.

        $carts = Cart::with('detailCarts')->where('status', $listStatusOrder[0])->get();

        foreach ($carts as $cart) {
            $updatedAt = $cart->updated_at;

            if ($updatedAt->diffInDays(Carbon::now()) > 30) {
                // Bắt đầu transaction
                DB::beginTransaction();

                try {
                    $cart->detailCarts()->delete();

                    $cart->update(['status' => $expiredStatus]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->error('An error occurred while updating cart status.');
                    return Command::FAILURE;
                }
            }
        }

        $this->info('Cart status updated successfully.');
        return Command::SUCCESS;
    }
}