<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\WebNewsLetters::factory(100)->create();
        \App\Models\WebContacts::factory(100)->create();
        \App\Models\WebVacancyApplications::factory(100)->create();
    }
}
