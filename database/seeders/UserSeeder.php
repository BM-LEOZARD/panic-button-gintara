<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'          => 'nama anda',
            'username'      => 'SuperAdmin',
            'role'          => 'SuperAdmin',
            'jenis_kelamin' => 'Laki-laki',
            'no_hp'         => '083199972370',
            'email'         => 'superadmin@example.com',
            'password'      => Hash::make('SuperAdmin123'),
        ]);
    }
}
