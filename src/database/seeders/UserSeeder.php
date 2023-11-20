<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create(['username' => 'admin', 'name' => 'مصطفی', 'family' => 'اکبری', 'mobile' => '09151260936', 'is_active' => Status::ACTIVE]);
        User::factory()->create(['username' => 'user1', 'name' => 'مصطفی', 'family' => 'اکبری', 'mobile' => '09151260936', 'is_active' => Status::ACTIVE]);
        User::factory()->create(['username' => 'user2', 'name' => 'مصطفی', 'family' => 'اکبری', 'mobile' => '09151260936', 'is_active' => Status::ACTIVE]);
        User::factory()->create(['username' => 'user', 'name' => 'مصطفی', 'family' => 'اکبری', 'mobile' => '09151260936', 'is_active' => Status::ACTIVE]);
        User::factory()->create(['username' => 'editor', 'name' => 'مصطفی', 'family' => 'اکبری', 'mobile' => '09151260936', 'is_active' => Status::ACTIVE]);
    }
}
