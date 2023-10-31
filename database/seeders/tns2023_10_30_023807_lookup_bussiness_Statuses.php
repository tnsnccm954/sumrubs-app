<?php

namespace Database\Seeders;

use App\Models\LookupBussinessStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tns2023_10_30_023807_lookup_bussiness_Statuses extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['systemname' => 'CLOSED_PERMANENTLY', 'display_name' => 'ธุรกิจนี้ปิดถาวร'],
            ['systemname' => 'CLOSED_TEMPORARILY', 'display_name' => 'ธุรกิจปิดชั่วคราว'],
            ['systemname' => 'OPERATIONAL', 'display_name' => 'ธุรกิจเปิดทําการปกติ'],
        ];

        foreach ($data as $key => $value) {
            $bussinessStatus = LookupBussinessStatus::firstOrCreate($value);
        }
    }
}
