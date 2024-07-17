<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // dd('x');
        $admin = Role::create(['name' => 'admin']);
        Role::create(['name' => 'gerente']);
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'usuario']);


        $permissionDeleteUser = Permission::create(['name' => 'delete_user']);

        $admin->givePermissionTo($permissionDeleteUser);

        // $user = User::inRandomOrder()->first();
        // $user->assignRole('manager');



        // $role1 = Role::create(['name' => 'admin']);
        // $role2 = Role::create(['name' => 'gerente']);
        // $role3 = Role::create(['name' => 'supervisor']);
        // $role4 = Role::create(['name' => 'usuario']);

        // $permissionDeleteUser = Permission::create(['name' => 'deletar_usuario']);

        // Permission::create(['name' => 'admin.task-status.index']);
        // Permission::create(['name' => 'admin.task-status.store']);
        // Permission::create(['name' => 'admin.task-status.update']);
        // Permission::create(['name' => 'admin.task-status.destroy']);

        // Permission::find(1)->assignRole(Role::find(1));
        // Permission::find(1)->assignRole(Role::find(2));

        // $role2 = Role::find(2);

        // Não utilizei esse método
        // Permission::find(1)->assignRole($role2);
        // Permission::find(2)->assignRole($role2);
        // Permission::find(3)->assignRole($role2);
        // Permission::find(4)->assignRole($role2);

        // $permission1 = Permission::find(1);
        // $permission2 = Permission::find(2);
        // $permission3 = Permission::find(3);
        // $permission4 = Permission::find(4);

        // $role2->givePermissionTo($permission1, $permission2, $permission3, $permission4);
    }
}
