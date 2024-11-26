<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outbound_id')->nullable()->constrained('outbounds')->onDelete('cascade');
            $table->foreignId('inbound_id')->nullable()->constrained('outbounds')->onDelete('cascade');
            $table->string('code_payment');
            $table->date('date')->nullable();
            // $table->decimal('total_payment', 10, 2);
            $table->decimal('paid', 20, 2)->nullable();
            $table->decimal('remaining', 20, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('bank')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
