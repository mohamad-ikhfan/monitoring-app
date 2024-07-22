<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPermissions = [
            'view-any role',
            'view role',
            'create  role',
            'update role',
            'delete role',
            'restore role',
            'force-delete role',

            'view-any user',
            'view user',
            'create  user',
            'update user',
            'delete user',
            'restore user',
            'force-delete user',

            'view-any poItem',
            'view poItem',
            'create poItem',
            'update poItem',
            'delete poItem',
            'restore poItem',
            'force-delete poItem',

            'view-any spkRelease',
            'view spkRelease',
            'create spkRelease',
            'update spkRelease',
            'delete spkRelease',
            'restore spkRelease',
            'force-delete spkRelease',

            'view-any targetPerModel',
            'view targetPerModel',
            'create targetPerModel',
            'update targetPerModel',
            'delete targetPerModel',
            'restore targetPerModel',
            'force-delete targetPerModel',

            'view-any productionOutsole',
            'view productionOutsole',
            'create productionOutsole',
            'update productionOutsole',
            'delete productionOutsole',
            'restore productionOutsole',
            'force-delete productionOutsole',

            'view-any productionUpper',
            'view productionUpper',
            'create productionUpper',
            'update productionUpper',
            'delete productionUpper',
            'restore productionUpper',
            'force-delete productionUpper',

            'view-any productionAssembly',
            'view productionAssembly',
            'create productionAssembly',
            'update productionAssembly',
            'delete productionAssembly',
            'restore productionAssembly',
            'force-delete productionAssembly',
        ];

        $dataRoles = [
            'dev',
            'admin',
            'monitoring',
            'spk',
            'outsole',
            'upper',
            'assembly'
        ];

        foreach ($dataPermissions as $dataPermission) {
            if (Permission::where('name', $dataPermission)->count() == 0) {
                Permission::create([
                    'name' => $dataPermission,
                    'guard_name' => 'web'
                ]);
            }
        }

        foreach ($dataRoles as $dataRole) {
            if (Role::where('name', $dataRole)->count() == 0) {
                $role = Role::create([
                    'name' => $dataRole,
                    'guard_name' => 'web'
                ]);
            } else {
                $role = Role::where('name', $dataRole)->first();
            }

            if ($dataRole == 'dev') {
                $role->givePermissionTo(Permission::all());
            }

            if ($dataRole == 'admin') {
                $role->givePermissionTo(Permission::whereIn('name', [
                    'view-any role',
                    'view role',
                    'create  role',
                    'update role',
                    'delete role',
                    'restore role',
                    'force-delete role',

                    'view-any user',
                    'view user',
                    'create  user',
                    'update user',
                    'delete user',
                    'restore user',
                    'force-delete user',
                ])->get());
            }

            if ($dataRole == 'dev') {
                $role->givePermissionTo(Permission::all());
            }

            if ($dataRole == 'spk') {
                $role->givePermissionTo(Permission::whereIn('name', [
                    'view-any spkRelease',
                    'view spkRelease',
                    'create spkRelease',
                    'update spkRelease',
                    'delete spkRelease',
                    'restore spkRelease',
                    'force-delete spkRelease',

                    'view-any targetPerModel',
                    'view targetPerModel',
                    'create targetPerModel',
                    'update targetPerModel',
                    'delete targetPerModel',
                    'restore targetPerModel',
                    'force-delete targetPerModel',
                ])->get());
            }

            if ($dataRole == 'spk') {
                $role->givePermissionTo(Permission::whereIn('name', [
                    'view-any spkRelease',
                    'view spkRelease',
                    'create spkRelease',
                    'update spkRelease',
                    'delete spkRelease',
                    'restore spkRelease',
                    'force-delete spkRelease',
                ])->get());
            }

            if ($dataRole == 'outsole') {
                $role->givePermissionTo(Permission::whereIn('name', [
                    'view-any productionOutsole',
                    'view productionOutsole',
                    'create productionOutsole',
                    'update productionOutsole',
                ])->get());
            }

            if ($dataRole == 'upper') {
                $role->givePermissionTo(Permission::whereIn('name', [
                    'view-any productionUpper',
                    'view productionUpper',
                    'create productionUpper',
                    'update productionUpper',
                ])->get());
            }

            if ($dataRole == 'assembly') {
                $role->givePermissionTo(Permission::whereIn('name', [
                    'view-any productionAssembly',
                    'view productionAssembly',
                    'create productionAssembly',
                    'update productionAssembly',
                ])->get());
            }
        }
    }
}