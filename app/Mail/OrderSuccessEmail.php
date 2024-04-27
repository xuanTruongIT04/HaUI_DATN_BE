<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderSuccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $billData;

    public function __construct($billData)
    {
        //
        $this->billData = $billData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userName = $this->billData->user->first_name . ' ' . $this->billData->user->last_name;
        $userEmail = $this->billData->user->email;
        $userPhone = $this->billData->user->phone;
        $infoUser = [
            'name' => $userName,
            'email' => $userEmail,
            'phone' => $userPhone,
        ];
        $orderCode = $this->billData->order->code;
        $orderDate = date('h:i:s - d/m/Y', strtotime($this->billData->order->order_date));
        $addressDelivery = $this->billData->order->address_delivery;
        $totalAmount = $this->billData->order->total_mount;
        $infoOrder = [
            'code' => $orderCode,
            'address_delivery' => $addressDelivery,
            'total_amount' => $totalAmount,
        ];

        // Process the product list
        $listProduct = [];
        foreach ($this->billData->order->cart->detailCarts as $cartItem) {
            $item = [
                'product' => [
                    'name' => $cartItem->product->name,
                    'price_sale' => $cartItem->price_sale,
                    'quantity' => $cartItem->quantity,
                    'subTotal' => number_format($cartItem->quantity * $cartItem->price_sale, 2),
                ],
            ];

            // Tìm ảnh chính cho mỗi sản phẩm
            $mainImage = collect($cartItem->product->images)->firstWhere('level', 0);
            $item['product']['mainImage'] = $mainImage ? $mainImage->link : null;
            $listProduct[] = $item;
        }

        $infoCoupon = $this->billData->order->coupon;
        $orderDate = date('h:i:s - d/m/Y', strtotime($this->billData->order->order_date));

        return $this->view('emails.confirm_success_order')
            ->from("nxt160602@gmail.com", "Lotus Thé")
            ->subject("[SABUJCHA_SHOP] Thông báo đặt hàng thành công")
            ->with([
                'infoUser' => $infoUser,
                'infoOrder' => $infoOrder,
                'orderDate' => $orderDate,
                'listProduct' => $listProduct,
                'infoCoupon' => $infoCoupon,
            ]);
    }

}
