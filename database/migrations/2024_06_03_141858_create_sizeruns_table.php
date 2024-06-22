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
        Schema::create('sizeruns', function (Blueprint $table) {
            $table->id();
            $table->string('size_3t')->nullable();
            $table->string('size_4')->nullable();
            $table->string('size_4t')->nullable();
            $table->string('size_5')->nullable();
            $table->string('size_5t')->nullable();
            $table->string('size_6')->nullable();
            $table->string('size_6t')->nullable();
            $table->string('size_7')->nullable();
            $table->string('size_7t')->nullable();
            $table->string('size_8')->nullable();
            $table->string('size_8t')->nullable();
            $table->string('size_9')->nullable();
            $table->string('size_9t')->nullable();
            $table->string('size_10')->nullable();
            $table->string('size_10t')->nullable();
            $table->string('size_11')->nullable();
            $table->string('size_11t')->nullable();
            $table->string('size_12')->nullable();
            $table->string('size_12t')->nullable();
            $table->string('size_13')->nullable();
            $table->string('size_13t')->nullable();
            $table->string('size_14')->nullable();
            $table->string('size_14t')->nullable();
            $table->string('size_15')->nullable();
            $table->string('size_15t')->nullable();
            $table->string('qty_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizeruns');
    }
};