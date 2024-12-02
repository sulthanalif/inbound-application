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
        Schema::create('inbounds', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('outbound_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->date('date');
            $table->string('sender_name')->nullable();
            $table->string('vehicle_number')->nullable();
            // $table->integer('qty')->default(0);
            $table->string('status')->default('Pending');
            $table->boolean('is_return')->default(false);
            $table->string('code_outbound')->nullable();
            $table->string('number')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbounds');
    }
};
