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
        Schema::create('tasks_status', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('bg_color')->default('#cecece');
            $table->string('color')->default('#cecece');
            $table->boolean('is_default')->default(false);
            $table->char('status', 1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_status');
    }
};
