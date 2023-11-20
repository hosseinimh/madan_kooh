<?php

namespace Database\Seeders;

use App\Constants\Permission as ConstantsPermission;
use App\Constants\Role as ConstantsRole;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => ConstantsRole::ADMIN, 'guard_name' => 'web']);
        Permission::create(['name' => ConstantsPermission::READ_WB_1]);
        Permission::create(['name' => ConstantsPermission::READ_WB_2]);
        Permission::create(['name' => ConstantsPermission::READ_ALL_WBS]);
        Permission::create(['name' => ConstantsPermission::EDIT_FACTOR_DESCRIPTION]);

        if ($user = User::find(1)) {
            $user->assignRole(ConstantsRole::ADMIN);
        }
        if ($user = User::find(2)) {
            $user->givePermissionTo(ConstantsPermission::READ_WB_1);
        }
        if ($user = User::find(3)) {
            $user->givePermissionTo(ConstantsPermission::READ_WB_2);
        }
        if ($user = User::find(4)) {
            $user->givePermissionTo(ConstantsPermission::READ_ALL_WBS);
        }
        if ($user = User::find(5)) {
            $user->givePermissionTo(ConstantsPermission::EDIT_FACTOR_DESCRIPTION);
        }
    }
}
