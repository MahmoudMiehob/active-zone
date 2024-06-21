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
        Schema::create('search_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('survice')->nullable();
            $table->string('country')->nullable();
            $table->date('start_at')->nullable();
            $table->double('price_start')->nullable();
            $table->double('price_end')->nullable();
            $table->integer('rating')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('search_archives');
    }
};
