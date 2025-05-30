<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
        $now = Carbon::now();

        $users = [
            [
                'name'              => '出品者',
                'email'             => 'seller@example.com',
                'email_verified_at' => $now,
                'password'          => Hash::make('sellerexample'),
                'image_path'        => null,
                'postal_code'       => '123-4567',
                'address'           => '大阪府大阪市北区梅田1-2-3',
                'building_name'     => 'グランフロント梅田タワーオフィスB棟10F',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'name'              => 'ゲスト',
                'email'             => 'guest@example.com',
                'email_verified_at' => $now,
                'password'          => Hash::make('guestexample'),
                'image_path'        => null,
                'postal_code'       => '345-6789',
                'address'           => '大阪府大阪市北区梅田3-4-5',
                'building_name'     => 'グランフロント梅田タワーオフィスA棟5F',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        DB::table('users')->insert($users);
    }
}
