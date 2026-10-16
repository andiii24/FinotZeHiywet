<?php

namespace Database\Seeders;

use App\Models\SpiritualResourceCategory;
use Illuminate\Database\Seeder;

class SpiritualResourceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Spiritual Teachings and Guidance',
                'description' => 'Teachings, sermons, and guidance for spiritual growth.',
            ],
            [
                'name' => 'Confession and Repentance',
                'description' => 'Resources related to confession, repentance, and spiritual renewal.',
            ],
            [
                'name' => 'Spiritual Advice',
                'description' => 'Practical spiritual advice for daily Christian life.',
            ],
            [
                'name' => 'Ethiopian Orthodox Tewahedo Church History',
                'description' => 'History and heritage of the Ethiopian Orthodox Tewahedo Church.',
            ],
            [
                'name' => 'Prayer and Devotional Resources',
                'description' => 'Prayers, liturgies, and devotionals for personal and communal worship.',
            ],
            [
                'name' => 'Books and Reading Materials',
                'description' => 'Books, texts, and reading materials for study and reflection.',
            ],
        ];

        foreach ($categories as $category) {
            SpiritualResourceCategory::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
