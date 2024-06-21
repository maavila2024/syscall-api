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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->foreignId('task_id')->references('id')->on('tasks')->cascadeOnUpdate();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnUpdate();
            // $table->foreignId('task_id');
            // $table->foreignId('task_id')->on('tasks')->references('id')
            //     ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
