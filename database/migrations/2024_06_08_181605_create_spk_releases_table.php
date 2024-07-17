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
        Schema::create('spk_releases', function (Blueprint $table) {
            $table->id();
            $table->date('release');
            $table->date('planning_start_outsole');
            $table->date('planning_start_upper');
            $table->date('planning_start_assembly');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_releases');
    }
};
