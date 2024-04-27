<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->string("code", 50)->unique();
            $table->decimal("percent", 5, 2);
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->tinyInteger("status")->comment('0: licensed, 1: pending, 2: trashed')->default("1");
            $table->softDeletes();
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
        Schema::dropIfExists('coupons');
    }
};
