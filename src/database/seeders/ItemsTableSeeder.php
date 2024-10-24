<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザーが存在するか確認し、取得
        $seller = User::first(); // シーディングされた最初のユーザーを取得
        if (!$seller) {
            // ユーザーが存在しない場合、作成する
            $seller = User::factory()->create();
        }

        $items = include database_path('seeders/data/items.php');

        foreach ($items as &$item) {
            // 静的な seller_id を動的に取得したユーザーの ID に置き換える
            $item['seller_id'] = $seller->id;
        }

        DB::table('items')->insert($items);
    }
}
