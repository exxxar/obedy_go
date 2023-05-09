<?php

namespace Database\Seeders;

use App\Models\Lottery;
use App\Models\LotteryPromocode;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LotteryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lottery::truncate();
        LotteryPromocode::truncate();

        $place_count = 40;
        Lottery::create([
            'image' => '/image/lottery/lottery_1.png',
            'title' => 'Праздничная лотерея',
            'description' => "Пробная праздничная лотерея!",
            'occupied_places' => [],
            'place_count' => $place_count,
            'free_place_count' => $place_count,
            'is_active' => true,
            'is_complete' => false,
            'date_end' => Carbon::now()->addMonths(5),
        ]);

        for ($i = 0; $i < $place_count; $i++) {
            LotteryPromocode::create([
                'code' => substr(Str::uuid(), 0, 16),
                'lottery_id'=>1,
            ]);

        }
    }
}
