<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'dummy',
            'email' => 'dummy@gmail.com',
            'password' => Hash::make('dummy'),
            'email_verified_at' => now(),
            'status' => 1,
        ]);
    }
}
