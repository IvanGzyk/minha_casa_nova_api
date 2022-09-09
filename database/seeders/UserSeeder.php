<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userMaster = User::create([
            'uuid'           => Str::uuid(),
            'name' => 'admin',
            'email' => 'ivangzyk@hotmail.com',
            'password' => bcrypt('admin'),
        ]);
    }
}
