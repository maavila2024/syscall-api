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
        Schema::create('interaction_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interaction_id')->references('id')->on('interactions')->cascadeOnUpdate();
            $table->string('name');
            $table->string('path');
            // $table->foreignId('interaction_id');
            // $table->foreignId('interaction_id')->on('interactions')
            //     ->references('id')->onDelete('cascade')
            //     ->onUpdate('cascade');
            // $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interaction_files');
    }
};
