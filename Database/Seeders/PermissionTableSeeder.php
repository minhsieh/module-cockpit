<?php

namespace Modules\Cockpit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Cockpit\Permission\Models\Role;
use Modules\Cockpit\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $model = config('auth.providers.users.model');

        $user = $model::where('email','side112358@gmail.com')->first();
        if(!$user) return;

        // Create default admin using permissions
        $admin_perms[] = Permission::findOrCreate('access_cockpit','中控後台進入','admin');
        $admin_perms[] = Permission::findOrCreate('manage_users','中控-管理所有使用者','admin');
        $admin_perms[] = Permission::findOrCreate('manage_teams','中控-管理所有團隊','admin');
        $admin_perms[] = Permission::findOrCreate('manage_logs','中控-查看Log','admin');
        $admin_perms[] = Permission::findOrCreate('manage_permissions','中控-管理所有權限','admin');
        $admin_perms[] = Permission::findOrCreate('manage_roles','中控-管理所有角色','admin');

        // Create default Team using permissions
        $team_perms[] = Permission::findOrCreate('manage_team_users','管理所有使用者','team');
        $team_perms[] = Permission::findOrCreate('manage_team_roles','管理所有角色','team');

        //Create Super Admin for MySelf
        $superadmin = Role::findOrCreate('superadmin','超級管理員');
        $team_admin = Role::findOrCreate('team_admin','團隊管理員');


        $superadmin->givePermissionTo($admin_perms);
        $team_admin->syncPermissions($team_perms);

        $user->assignRole('superadmin');

        echo "Permission seed Success!".PHP_EOL;
    }
}
