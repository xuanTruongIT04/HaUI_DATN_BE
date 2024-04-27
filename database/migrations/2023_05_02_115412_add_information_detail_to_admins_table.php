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
        Schema::table('admins', function (Blueprint $table) {
            $table->tinyInteger("gender")->nullable()->after("name")->comment('0: female, 1: male, 2: other genders');
            $table->string("address", 255)->nullable()->after("gender");
            $table->char("phone", 20)->nullable()->after("address");
            $table->string("avatar", 255)->nullable()->after("password");
            $table->string("role", 50)->nullable()->after("avatar");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->dropColumn("gender");
            $table->dropColumn("address");
            $table->dropColumn("phone");
            $table->dropColumn("avatar");
            $table->dropColumn("role");
        });
    }
};