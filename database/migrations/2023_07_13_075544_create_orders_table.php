<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50);
            $table->string("address_delivery", 255)->nullable();;
            $table->tinyInteger("payment_method")->comment('0: cash, 1: scannerQR')->nullable();;
            $table->decimal("total_mount", 10, 2);
            $table->dateTime("order_date")->default(now());
            $table->tinyInteger("status")->comment('0: ordered, 1: processing, 2: paid, 3:cancelled')->default("0");
            $table->unsignedBigInteger("coupon_id")->nullable();
            $table->unsignedBigInteger("cart_id");
            $table->timestamps();
            $table->foreign("coupon_id")
                ->references("id")
                ->on("coupons")
                ->onUpdate("cascade");
            $table->foreign("cart_id")
                ->references("id")
                ->on("carts")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};