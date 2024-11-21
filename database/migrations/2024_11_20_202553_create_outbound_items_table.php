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
        Schema::create('outbound_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outbound_id')->constrained()->onDelete('cascade');
            $table->foreignId('goods_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('sub_total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_items');
    }
};
