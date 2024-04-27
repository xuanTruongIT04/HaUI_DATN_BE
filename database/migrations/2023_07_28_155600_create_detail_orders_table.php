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
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id();
            $table->integer("quantity");
            $table->decimal("price_sale", 10, 2);
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("product_id");
            $table->foreign("order_id")
                ->references("id")
                ->on("orders")
                ->onUpdate("cascade");
            $table->foreign("product_id")
                ->references("id")
                ->on("products")
                ->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_orders');
    }
};