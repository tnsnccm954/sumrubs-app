<?php

namespace Database\Seeders;

use App\Models\LookupCuisineCulture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tns2023_10_30_024409_lookup_cuisine_cultures extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'parent_cuisine_culture_id' => null,
                'name' => 'Chinese food',
                'display_name' => 'อาหารจีน',
            ],
            [
                'parent_cuisine_culture_id' => null,
                'name' => 'Thai food',
                'display_name' => 'อาหารไทย',
            ],
            [
                'parent_cuisine_culture_id' => 2,
                'name' => 'Isan Thai food',
                'display_name' => 'อาหารอีสาน',
            ],
            [
                'parent_cuisine_culture_id' => 2,
                'name' => 'Northern Thai food',
                'display_name' => 'อาหารเหนือ',
            ],
            [
                'parent_cuisine_culture_id' => 2,
                'name' => 'South Thai Food',
                'display_name' => 'อาหารใต้',
            ],
            [
                'parent_cuisine_culture_id' => null,
                'name' => 'Japanese food',
                'display_name' => 'อาหารญี่ปุ่น',
            ],
            [
                'parent_cuisine_culture_id' => null,
                'name' => 'Indian food',
                'display_name' => 'อาหารอินเดีย',
            ],
        ];

        foreach ($data as $key => $value) {
            $cuisineCulture = LookupCuisineCulture::firstOrCreate($value);
        }
    }
}
