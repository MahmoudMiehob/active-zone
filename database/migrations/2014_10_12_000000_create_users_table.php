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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->string('email')->unique()->nullable();
            $table->string('temp_email')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('points')->default('0');
            $table->date('birthday')->nullable();
            $table->string('sex')->nullable();

            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();

            $table->string('temp_postal_code')->nullable();
            $table->string('temp_phone')->nullable();

            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();

            $table->string('image')->nullable();
            $table->integer('role_id')->default(1);
            $table->rememberToken();

            $table->string('theme')->nullable()->default('default');
            $table->string('theme_color')->nullable();
            $table->integer('survice_id')->default(null)->nullable();

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
        Schema::dropIfExists('users');
    }
};
