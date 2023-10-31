<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call([
        //     tns2023_10_24_00_13_59_user_role_seeder::class,
        //     tns2023_10_24_00_14_16_projects_permissions_seeder::class,
        //     // \ThailandAddressSeeder::class,
        //     tns2023_10_30_002500_reviews_permissions_seeder::class,
        //     tns2023_10_30_023234_lookup_food_types::class,
        //     tns2023_10_30_023807_lookup_bussiness_Statuses::class,
        //     tns2023_10_30_024409_lookup_cuisine_cultures::class,
        // ]);


        // $allSeeds = scandir(database_path('\seeders'));
        // $mainClassName = basename($this::class);
        // $filteredSeeds = array_filter($allSeeds, function ($seed) use ($mainClassName) {
        //     $isMainClass = preg_match("/$mainClassName/i", $seed);
        //     $isPhpSeeder = preg_match("/.php/i", $seed);
        //     return !$isMainClass && $isPhpSeeder;
        // });

        // $mappedSeeds = array_map(function ($seed) {
        //     return new (str_replace('.php', "::class", $seed));
        // }, $filteredSeeds);

        // error_log(json_encode($mappedSeeds));
        // $this->call($mappedSeeds);


        $allSeeds = scandir(database_path('seeders'));
        $mainClassName = basename($this::class);
        $filteredSeeds = array_filter($allSeeds, function ($seed) use ($mainClassName) {
            $isMainClass = preg_match("/$mainClassName/i", $seed);
            $isPhpSeeder = preg_match("/.php/i", $seed);
            return !$isMainClass && $isPhpSeeder;
        });

        $mappedSeeds = array_map(function ($seed) {
            $className = str_replace('.php', '', $seed);
            $seedClass = app()->make("Database\Seeders\\" . $className)::class;
            return $seedClass;
        }, $filteredSeeds);

        foreach ($mappedSeeds as $seedClass) {
            $this->call($seedClass);
        }
    }
}
