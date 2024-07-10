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
        Schema::table('tasks', function (Blueprint $table) {
            $table->char('task_type', 1)->after('segment');
            $table->string('task_code')->after('task_type');
            $table->text('system_screen')->nullable()->change();
            $table->date('expected_date')->nullable()->change();
            $table->date('finish_date')->nullable()->change();

            // Remover a coluna review_justification corretamente
            $table->dropColumn('review_justification');

            // Soltar a chave estrangeira antes de modificar a coluna
            $table->dropForeign(['responsible_id']);
            $table->foreignId('responsible_id')->nullable()->change();
            // Recriar a chave estrangeira
            $table->foreign('responsible_id')->references('id')->on('users')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('task_type');
            $table->dropColumn('task_code');
            $table->text('system_screen')->nullable(false)->change();
            $table->date('expected_date')->nullable(false)->change();
            $table->date('finish_date')->nullable(false)->change();

            // Adicionar a coluna review_justification de volta
            $table->text('review_justification')->nullable();

            // Soltar e recriar a chave estrangeira para reverter as mudanças
            $table->dropForeign(['responsible_id']);
            $table->foreignId('responsible_id')->nullable(false)->change();
            $table->foreign('responsible_id')->references('id')->on('users')->cascadeOnUpdate();
        });
    }
};