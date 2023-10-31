<?php

namespace Database\Seeders;

use App\Models\LookupPermission;
use App\Models\LookupRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tns2023_10_30_002500_reviews_permissions_seeder extends Seeder
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
                'object' => 'reviews',
            ], ['display_name' => 'ดู review',]),
            'create' => LookupPermission::firstOrCreate([
                'action' => 'create',
                'object' => 'reviews',
            ], ['display_name' => 'สร้าง review']),
            'update' => LookupPermission::firstOrCreate([
                'action' => 'update',
                'object' => 'reviews',
            ], ['display_name' => 'แก้ไข review',]),
            'delete' => LookupPermission::firstOrCreate([
                'action' => 'delete',
                'object' => 'reviews',
            ], ['display_name' => 'ลบ review',]),
        ]);

        $superDev = LookupRole::where('system_name', 'super_dev')->first();
        $superDev->permissions()->attach($permissions->pluck('id'));
    }
}
