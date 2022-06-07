<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GroupRoleSeeder extends Seeder
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
                'group_role_name' => 'IT',
            ],
            [
                'group_role_name' => 'GUARD',
            ],
    
            
        ];

        \App\Models\GroupRole::insertOrIgnore($data);


    }
}
