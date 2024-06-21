<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create([
            'name' => 'AGCO Corp',
            'token' => '4dfc8967-43d8-4f97-87f5-9576033ac218',
        ]);
        User::create([
            'first_name' => 'Pato',
            'token' => 'e8d1c79f-7f7c-41d2-b821-91cc8171b1a1',
            'email' => 'pato@internacional.com.br',
            'password' => bcrypt('1234567a'),
            'default_team_id' => 1
        ]);
    }
}
