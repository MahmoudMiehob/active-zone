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
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('age');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('start_at');
            $table->date('end_at');
            $table->string('coupon')->nullable();
            $table->integer('baby_number');
            $table->integer('adult_number');
            $table->double('baby_price');
            $table->double('adult_price');
            $table->double('tax_price');
            $table->double('total_price');
            $table->string('status');
            $table->boolean('paid')->default(false);
            $table->string('tran_ref')->nullable();

            $table->integer('minisurvice_id')->unsigned()->nullable(); //TODO
            $table->foreign('minisurvice_id')->references('id')->on('minisurvices')->onDelete('cascade');

            $table->integer('region_id')->unsigned()->nullable(); //TODO
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('provider_id')->unsigned()->nullable(); //TODO
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('reservations');
    }
};
