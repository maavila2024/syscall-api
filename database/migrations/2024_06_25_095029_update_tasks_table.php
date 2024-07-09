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
            $table->dropColumn('review_justification')->nullable();

            $table->dropForeign(['responsible_id']);
            $table->foreignId('responsible_id')->nullable()->change();
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
            $table->text('system_screen')->change();
            $table->date('expected_date')->change();
            $table->date('finish_date')->change();
            $table->text('review_justification')->nullable()->change();

            $table->dropForeign(['responsible_id']);
            $table->foreignId('responsible_id')->change();
            $table->foreign('responsible_id')->references('id')->on('users')->cascadeOnUpdate();
        });
    }
};
