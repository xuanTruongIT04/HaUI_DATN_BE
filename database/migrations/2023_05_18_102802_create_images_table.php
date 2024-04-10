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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("color_id");
            $table->string("link", 255);
            $table->tinyInteger("level")->default("1")->comment('0: main, 1: sub')->default("1");
            $table->string("description", 255)->nullable()->default("");
            $table->tinyInteger("status")->comment('0: licensed, 1: pending, 2: trashed')->default("1");
            $table->foreign("product_id")
                ->references("id")
                ->on("products")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            $table->foreign("color_id")
                ->references("id")
                ->on("colors")
                ->onDelete("cascade")
                ->onUpdate("cascade");
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
        Schema::dropIfExists('images');
    }
};
