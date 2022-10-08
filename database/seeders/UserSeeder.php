<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            [
                'username' => 'admin',
                'lname' => 'Pradia',
                'fname' => 'Gerliza',
                'mname' => 'P',
                'sex' => 'FEMALE',
                'email' => 'Gerliza@light.com',
                'contact_no' => '09164578599',
                'role' => 'ADMINISTRATOR',
                'group_role_id' => 1,
                'password' => Hash::make('a')
            ],


          
        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
