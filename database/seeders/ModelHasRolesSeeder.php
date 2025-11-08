<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insere o role para o usuário (model_id = 11)
        DB::table('model_has_roles')->insertOrIgnore([
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 11,
                'team_id' => 1,
            ],
        ]);
    }
}

