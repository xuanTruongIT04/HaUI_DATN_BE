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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("brand_id");
            $table->string("code", 255);
            $table->string("name", 255)->unique();
            $table->float("price");
            $table->integer("discount")->nullable();
            $table->integer("qty_import")->nullable();
            $table->integer("qty_sold");
            $table->text("description")->nullable();
            $table->text("detail");
            $table->string("slug", 255)->unique();
            $table->integer("rate")->nullable();
            $table->tinyInteger("status")->comment('0: licensed, 1: pending, 2: trashed')->default("1");
            $table->foreign("category_id")
                  ->references("id")
                  ->on("categories")
                  ->onDelete("cascade")
                  ->onUpdate("cascade");
            $table->foreign("brand_id")
                  ->references("id")
                  ->on("brands")
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
        Schema::dropIfExists('products');
    }
};