<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minisurvices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('imagepath',1024);
            $table->string('description_ar')->nullable();
            $table->string('description_en')->nullable();
            $table->string('location')->nullable();
            $table->integer('rating');
            $table->string('address_ar');
            $table->string('address_en');
            $table->double('baby_price');
            $table->double('adult_price');
            $table->integer('points');
            $table->integer('humannumber')->nullable();
            $table->integer('reservationnumber')->default(0)->nullable();
            $table->double('tax')->default('0');
            $table->date('start_at');
            $table->date('end_at');
            $table->boolean('isOffer')->default(0)->nullable();
            $table->boolean('isFamily')->default(0)->nullable();
            $table->string('phone')->nullable();
            $table->integer('provider_id');

            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');

            $table->integer('survice_id')->unsigned();
            $table->foreign('survice_id')->references('id')->on('survices')->onDelete('cascade');

            $table->integer('subsurvice_id')->unsigned();
            $table->foreign('subsurvice_id')->references('id')->on('subsurvices')->onDelete('cascade');

            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');

            $table->string('description_region_ar')->nullable();
            $table->string('description_region_en')->nullable();

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
        Schema::dropIfExists('minisurvices');
    }
};
