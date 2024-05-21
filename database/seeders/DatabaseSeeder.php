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
        // run userseeder and langugae seeder
        $this->call([
            UserSeeder::class,
            LanguageSeeder::class,
            SettingSeeder::class,
            DummyUserSeeder::class,
        ]);
    }
}
