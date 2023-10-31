<?php

namespace Database\Seeders;

use App\Models\LookupFoodType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tns2023_10_30_023234_lookup_food_types extends Seeder
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
                'parent_food_type_id' => null,
                'category_type' => 'food_periods',
                'name' => 'Food Periods',
                'display_name' => 'ช่วงการกิน',
                'is_modifiable' => 0,
            ],
            [
                'parent_food_type_id' => null,
                'category_type' => 'food_categories',
                'name' => 'Food Category',
                'display_name' => 'ประเภทอาหาร',
                'is_modifiable' => 0,
            ],
            [
                'parent_food_type_id' => null,
                'category_type' => 'drinks',
                'name' => 'Drinks',
                'display_name' => 'เครื่องดื่ม',
                'is_modifiable' => 0,
            ],
            [
                'parent_food_type_id' => 1,
                'category_type' => 'food_period',
                'name' => 'Breaks/Snacks',
                'display_name' => 'อาหารว่าง',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 1,
                'category_type' => 'food_period',
                'name' => 'Breakfast',
                'display_name' => 'อาหารเช้า',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 1,
                'category_type' => 'food_period',
                'name' => 'Lunch',
                'display_name' => 'อาหารเที่ยง',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 1,
                'category_type' => 'food_period',
                'name' => 'Dinner',
                'display_name' => 'อาหารเย็น',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 2,
                'category_type' => 'food_category',
                'name' => 'Single-dish meal',
                'display_name' => 'อาหารจานเดียว',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 2,
                'category_type' => 'food_category',
                'name' => 'Noodle',
                'display_name' => 'ก๋วยเตี๋ยว/เส้น',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 2,
                'category_type' => 'food_category',
                'name' => 'Barbeque',
                'display_name' => 'หมูกระทะ',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 2,
                'category_type' => 'food_category',
                'name' => 'Meal sets',
                'display_name' => 'อาหารชุด',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 3,
                'category_type' => 'drinks',
                'name' => 'Milk Tea',
                'display_name' => 'ชานม',
                'is_modifiable' => 1,
            ],
            [
                'parent_food_type_id' => 3,
                'category_type' => 'drinks',
                'name' => 'Infused Tea',
                'display_name' => 'น้ำสมุนไพร',
                'is_modifiable' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            $foodType = LookupFoodType::firstOrCreate($value);
        }
    }
}
