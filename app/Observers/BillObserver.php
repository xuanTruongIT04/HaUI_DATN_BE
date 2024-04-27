<?php

namespace App\Observers;

use App\Models\Bill;
use App\Helpers\Constant;

class BillObserver
{
    /**
     * Handle the Bill "created" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function created(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "updated" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function updated(Bill $bill)
    {
        $statusBill = array_keys(Constant::STATUS_BILL);
        $statusOrder = array_keys(Constant::STATUS_ORDER);
        $newStatus = $bill->status;
        if ($newStatus == $statusBill[1]) {
            $order = $bill->orders()->first();
            if ($order) {
                $order->update(['status' => $statusOrder[2]]);
            }
        } else {
            $order = $bill->orders()->first();
            if ($order) {
                $order->update(['status' => $statusOrder[0]]);
            }
        }
    }

    /**
     * Handle the Bill "deleted" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function deleted(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "restored" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function restored(Bill $bill)
    {
        //
    }

    /**
     * Handle the Bill "force deleted" event.
     *
     * @param  \App\Models\Bill  $bill
     * @return void
     */
    public function forceDeleted(Bill $bill)
    {
        //
    }
}