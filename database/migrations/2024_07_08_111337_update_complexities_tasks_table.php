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
        // Adiciona a coluna complexity_id como nullable
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('complexity_id')->nullable()->after('priority_justification');
            $table->text('complexity_justification')->nullable()->after('complexity_id');
        });

        // Agora adiciona a chave estrangeira
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('complexity_id')->references('id')->on('complexities')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['complexity_id']);
            $table->dropColumn('complexity_justification');
            $table->dropColumn('complexity_id');
        });
    }
};
