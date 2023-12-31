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
                'object' => 'places',
            ], ['display_name' => 'ดู place',]),
            'create' => LookupPermission::firstOrCreate([
                'action' => 'create',
                'object' => 'places',
            ], ['display_name' => 'สร้าง place']),
            'update' => LookupPermission::firstOrCreate([
                'action' => 'update',
                'object' => 'places',
            ], ['display_name' => 'แก้ไข place',]),
            'delete' => LookupPermission::firstOrCreate([
                'action' => 'delete',
                'object' => 'places',
            ], ['display_name' => 'ลบ place',]),
        ]);

        $superDev = LookupRole::where('system_name', 'super_dev')->first();
        $superDev->permissions()->attach($permissions->pluck('id'));
    }
}
