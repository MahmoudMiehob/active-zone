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
            $table->increments('id');
            $table->string('type');
            $table->string('coupon');
            $table->double('value');
            $table->date('start_at');
            $table->date('end_at');

            $table->integer('provider_id')->unsigned();
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('minisurvice_id')->unsigned();
            $table->foreign('minisurvice_id')->references('id')->on('minisurvices')->onDelete('cascade');

            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade'); 
            
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
