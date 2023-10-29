<?php

namespace Database\Seeders;

use App\Models\LookupPermission;
use App\Models\LookupRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tns2023_10_24_00_14_16_projects_permissions_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = collect([
            'view' => LookupPermission::firstOrCreate([
                'action' => 'view',
                'object' => 'portfolio_projects',
                'display_name' => 'ดู port_project',
            ]),
            'create' => LookupPermission::firstOrCreate([
                'action' => 'create',
                'object' => 'portfolio_projects',
                'display_name' => 'สร้าง port_project',
            ]),
            'update' => LookupPermission::firstOrCreate([
                'action' => 'update',
                'object' => 'portfolio_projects',
                'display_name' => 'แก้ไข port_project',
            ]),
            'delete' => LookupPermission::firstOrCreate([
                'action' => 'delete',
                'object' => 'portfolio_projects',
                'display_name' => 'ลบ port_project',
            ]),
        ]);

        $superDev = LookupRole::where('system_name', 'super_dev')->first();
        $superDev->permissions()->attach($permissions->pluck('id'));
    }
}
