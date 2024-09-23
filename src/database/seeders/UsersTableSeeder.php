<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'     => 'テスター',
            'email'    => 'tester@example.com',
            'password' => Hash::make('testerexample'),
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市北区梅田1-2-3',
            'building_name' => 'グランフロント梅田タワーオフィスB棟10F',
        ]);
    }
}
