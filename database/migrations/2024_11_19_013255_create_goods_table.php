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
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sk');
            $table->float('length');
            $table->float('width');
            $table->float('height');
            $table->float('weight');
            $table->text('description')->nullable();
            $table->string('condition');
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
