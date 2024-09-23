<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            [
                'item_id'    => 1,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 2,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 3,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 4,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 5,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 6,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 7,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 8,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 9,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'item_id'    => 10,
                'seller_id'  => 1,
                'buyer_id'   => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}