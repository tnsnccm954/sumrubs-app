<?php

namespace Database\Seeders;

use App\Models\LookupRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tns2023_10_24_00_13_59_user_role_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'super_admin' => LookupRole::firstorCreate([
                'system_name' => 'super_dev',
            ], [
                'name' => 'ผู้จัดการระบบ',
                'access_key' => config("roleaccess.superdev", ""),
                'modifilable' => false,
            ]),
        ];
    }
}
