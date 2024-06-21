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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->char('segment', 1);
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreignId('responsible_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreignId('task_status_id')->references('id')->on('tasks_status')->cascadeOnUpdate();
            $table->text('system_screen');
            $table->text('observation')->nullable();
            $table->foreignId('priority_id')->references('id')->on('priorities')->cascadeOnUpdate();
            $table->text('priority_justification')->nullable();
            $table->text('review_justification')->nullable();
            $table->date('expected_date');
            $table->date('finish_date');
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
        Schema::dropIfExists('tasks');
    }
};
