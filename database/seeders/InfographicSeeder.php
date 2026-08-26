<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InfographicSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        $items = [
            [
                'publishdate' => Carbon::now('Asia/Jakarta')->subDays(30)->format('Y-m-d H:i:s'),
                'titleID' => 'Area terbakar Indonesia 2000-2024',
                'titleEN' => 'Indonesia burned area 2000-2024',
                'category' => 'annual',
                'descriptionID' => '<p>Rekapitulasi luas area terbakar selama 25 tahun: 9,5 juta ha pernah terbakar, rata-rata 0,8 juta ha per tahun.</p>',
                'descriptionEN' => '<p>Summary of burned area over 25 years: 9.5 million ha burned at least once, averaging 0.8 million ha per year.</p>',
            ],
            [
                'publishdate' => Carbon::now('Asia/Jakarta')->subDays(60)->format('Y-m-d H:i:s'),
                'titleID' => 'Distribusi musim kebakaran September-November',
                'titleEN' => 'September-November fire season distribution',
                'category' => 'monthly',
                'descriptionID' => '<p>61% area terbakar hingga 2024 terjadi pada periode September sampai November.</p>',
                'descriptionEN' => '<p>61% of burned area up to 2024 occurred between September and November.</p>',
            ],
        ];

        foreach ($items as $item) {
            DB::table('infographic')->updateOrInsert(
                ['slug' => Str::slug($item['titleEN'])],
                array_merge($item, [
                    'imgID' => 'sample-infographic-id.jpg',
                    'imgEN' => 'sample-infographic-en.jpg',
                    'slug' => Str::slug($item['titleEN']),
                    'status' => '1',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
