<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('order_id');
        $table->decimal('total_amount', 12, 2);
        $table->string('payment_method', 50);
        $table->enum('payment_status', ['paid', 'unpaid', 'failed'])->default('unpaid');
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
