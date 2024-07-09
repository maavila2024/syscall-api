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
            $table->foreignId('complexity_id')->references('id')->on('complexities')->cascadeOnUpdate()->nullable()->after('priority_justification');
            $table->text('complexity_justification')->nullable()->after('complexity_id');
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
        });
    }
};
