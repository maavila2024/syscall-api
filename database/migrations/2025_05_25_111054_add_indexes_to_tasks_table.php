<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('owner_id');
            $table->index('responsible_id');
            $table->index('priority_id');
            $table->index('expected_date');
            $table->index('finish_date');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['owner_id']);
            $table->dropIndex(['responsible_id']);
            $table->dropIndex(['priority_id']);
            $table->dropIndex(['expected_date']);
            $table->dropIndex(['finish_date']);
        });
    }
};
