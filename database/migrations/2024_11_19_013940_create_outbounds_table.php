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
        Schema::create('outbounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('delivery_area_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('pickup_area_id')->nullable()->constrained('areas')->onDelete('cascade')->nullable();
            $table->string('code')->unique();
            $table->date('date');
            $table->string('sender_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->decimal('total_price', 20, 2)->nullable();
            $table->string('number')->nullable();
            $table->string('status')->default('Pending');
            $table->string('status_payment')->default('Unpaid');
            $table->string('payment')->nullable();
            $table->boolean('is_return')->default(false);
            $table->boolean('is_resend')->default(false);
            $table->boolean('order')->default(false);
            $table->string('code_inbound')->nullable();
            $table->string('move_to')->nullable();
            $table->string('move_from')->nullable();
            // $table->string('payment_method')->nullable();
            // $table->string('bank')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbounds');
    }
};
