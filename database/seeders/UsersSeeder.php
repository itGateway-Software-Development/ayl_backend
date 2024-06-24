<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'phone' => '091111111',
                'type' => 'system',
                'password' => bcrypt('Ayl123!@#'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
