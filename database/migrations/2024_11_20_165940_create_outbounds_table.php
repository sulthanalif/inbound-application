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
            $table->string('code')->unique();
            $table->date('date');
            $table->string('sender_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->decimal('total_price', 20, 2);
            $table->string('status')->default('Unpaid');
            $table->string('status_payment')->default('Pending');
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
