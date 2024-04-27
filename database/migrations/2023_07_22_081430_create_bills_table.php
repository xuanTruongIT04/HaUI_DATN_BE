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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("status")->comment('0: unPaid, 1: paid')->default("0");
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("user_id");
            $table->foreign("order_id")
                ->references("id")
                ->on("orders")
                ->onUpdate("cascade");
            $table->foreign("user_id")
                ->references("id")
                ->on("users")
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
        Schema::dropIfExists('bills');
    }
};