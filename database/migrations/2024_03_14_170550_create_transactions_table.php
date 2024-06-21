<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tran_ref')->nullable();

            $table->string('tran_type')->nullable();

            $table->double('cart_amount')->nullable();
            $table->double('tran_total')->default(0);

            $table->foreignId('reservation_id')->nullable();
            $table->foreignId('user_id')->nullable();

            $table->boolean('success')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
