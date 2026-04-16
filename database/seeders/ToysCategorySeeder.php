<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ToysCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Category::unsetEventDispatcher();

            // Create Toys & Games store category
            $storeCategory = StoreCategory::firstOrCreate(
                ['slug' => 'toys-games'],
                [
                    'name' => ['en' => 'Toys & Games', 'ar' => 'الألعاب والألعاب'],
                    'description' => [
                        'en' => 'Kids toys, board games, outdoor play, and educational toys for all ages',
                        'ar' => 'ألعاب الأطفال وألعاب الطاولة والألعاب الخارجية والألعاب التعليمية لجميع الأعمار',
                    ],
                ]
            );

            $stores = [
                [
                    'name'        => ['en' => 'Toy Universe', 'ar' => 'عالم الألعاب'],
                    'description' => ['en' => 'The ultimate destination for children\'s toys, from action figures to educational kits and everything in between.', 'ar' => 'الوجهة المثالية لألعاب الأطفال، من الشخصيات إلى الأطقم التعليمية وكل شيء بينهما.'],
                    'email'       => 'toyuniverse@store.com',
                    'phone'       => '+1234567950',
                    'keywords'    => ['toys', 'kids', 'action figures', 'educational'],
                ],
                [
                    'name'        => ['en' => 'Kids Kingdom', 'ar' => 'مملكة الأطفال'],
                    'description' => ['en' => 'Board games, puzzles, card games, and family entertainment for every age group.', 'ar' => 'ألعاب الطاولة والألغاز وألعاب البطاقات والترفيه العائلي لكل فئة عمرية.'],
                    'email'       => 'kidskingdom@store.com',
                    'phone'       => '+1234567951',
                    'keywords'    => ['board games', 'puzzles', 'family', 'kids'],
                ],
                [
                    'name'        => ['en' => 'Play & Learn', 'ar' => 'العب وتعلم'],
                    'description' => ['en' => 'STEM kits, science experiments, art supplies, and educational toys that make learning fun.', 'ar' => 'أطقم STEM وتجارب علمية ومستلزمات فنية وألعاب تعليمية تجعل التعلم ممتعاً.'],
                    'email'       => 'playlearn@store.com',
                    'phone'       => '+1234567952',
                    'keywords'    => ['STEM', 'educational', 'science', 'art'],
                ],
                [
                    'name'        => ['en' => 'Outdoor Playland', 'ar' => 'ملعب الهواء الطلق'],
                    'description' => ['en' => 'Outdoor toys, sports equipment for kids, bikes, scooters, and active play gear.', 'ar' => 'ألعاب خارجية ومعدات رياضية للأطفال ودراجات وسكوترات ومعدات اللعب النشط.'],
                    'email'       => 'outdoorplayland@store.com',
                    'phone'       => '+1234567953',
                    'keywords'    => ['outdoor', 'bikes', 'scooters', 'sports', 'kids'],
                ],
            ];

            $productTemplates = $this->getProductTemplates();

            foreach ($stores as $storeData) {
                $store = Store::create([
                    'name'          => $storeData['name'],
                    'slug'          => Str::slug($storeData['name']['en']),
                    'description'   => $storeData['description'],
                    'email'         => $storeData['email'],
                    'phone'         => $storeData['phone'],
                    'password'      => 'password123',
                    'category_id'   => $storeCategory->id,
                    'keywords'      => $storeData['keywords'],
                    'delivery_time' => rand(30, 90),
                    'is_active'     => true,
                ]);

                // 5 product sub-categories for each store
                $subCategories = [];
                foreach ($this->getSubCategoryNames() as $catName) {
                    $subCategories[] = Category::create([
                        'name'        => $catName,
                        'slug'        => Str::slug($catName['en'] . '-' . $store->id),
                        'description' => ['en' => 'Browse ' . strtolower($catName['en']), 'ar' => 'تصفح ' . $catName['ar']],
                        'store_id'    => $store->id,
                        'is_active'   => true,
                    ]);
                }

                foreach ($productTemplates as $index => $product) {
                    $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23));
                    Product::create([
                        'uuid'         => (string) Str::uuid(),
                        'name'         => $product['name'],
                        'slug'         => Str::slug($product['name']['en'] . '-' . $store->id),
                        'description'  => $product['description'],
                        'price'        => $product['price'],
                        'compare_price' => $product['compare_price'],
                        'store_id'     => $store->id,
                        'category_id'  => $subCategories[$index % 5]->id,
                        'keywords'     => $product['keywords'],
                        'quantity'     => rand(20, 150),
                        'is_active'    => true,
                        'is_accepted'  => true,
                        'created_at'   => $createdAt,
                        'updated_at'   => $createdAt,
                    ]);
                }

                $this->command->info("Created store: {$storeData['name']['en']} with " . count($productTemplates) . " products.");
            }

            $this->command->info('Toys & Games category seeded successfully.');
        });
    }

    private function getSubCategoryNames(): array
    {
        return [
            ['en' => 'Action Figures',    'ar' => 'شخصيات الحركة'],
            ['en' => 'Board Games',        'ar' => 'ألعاب الطاولة'],
            ['en' => 'Educational Toys',   'ar' => 'الألعاب التعليمية'],
            ['en' => 'Outdoor & Sports',   'ar' => 'الهواء الطلق والرياضة'],
            ['en' => 'Arts & Crafts',      'ar' => 'الفنون والحرف اليدوية'],
        ];
    }

    private function getProductTemplates(): array
    {
        return [
            ['name' => ['en' => 'LEGO Classic Creative Bricks Set', 'ar' => 'مجموعة طوب ليغو الكلاسيكية الإبداعية'], 'description' => ['en' => '900-piece LEGO set with classic colorful bricks for unlimited creative building.', 'ar' => 'مجموعة ليغو من 900 قطعة مع طوب ملونة كلاسيكية للبناء الإبداعي غير المحدود.'], 'price' => 39.99, 'compare_price' => 54.99, 'keywords' => ['LEGO', 'building blocks', 'creative']],
            ['name' => ['en' => 'Remote Control Racing Car', 'ar' => 'سيارة سباق بالتحكم عن بعد'], 'description' => ['en' => 'High-speed RC car with 4WD, rechargeable battery, and durable design for off-road fun.', 'ar' => 'سيارة RC عالية السرعة بنظام 4WD وبطارية قابلة للشحن وتصميم متين للمتعة في الطرق الوعرة.'], 'price' => 49.99, 'compare_price' => 69.99, 'keywords' => ['RC car', 'remote control', 'racing']],
            ['name' => ['en' => 'Monopoly Classic Board Game', 'ar' => 'لعبة مونوبولي الكلاسيكية'], 'description' => ['en' => 'The classic family property trading board game for 2-8 players, hours of entertainment.', 'ar' => 'لعبة الطاولة العائلية الكلاسيكية لتداول العقارات لـ 2-8 لاعبين، ساعات من الترفيه.'], 'price' => 24.99, 'compare_price' => 34.99, 'keywords' => ['board game', 'Monopoly', 'family']],
            ['name' => ['en' => 'Kids Science Experiment Kit', 'ar' => 'طقم تجارب العلوم للأطفال'], 'description' => ['en' => '50+ science experiments kit for kids aged 6+, includes everything needed for safe exploration.', 'ar' => 'طقم يحتوي على أكثر من 50 تجربة علمية للأطفال 6 سنوات فأكثر.'], 'price' => 34.99, 'compare_price' => 49.99, 'keywords' => ['science kit', 'STEM', 'experiments']],
            ['name' => ['en' => 'Stuffed Animal Teddy Bear', 'ar' => 'دب محشو للأطفال'], 'description' => ['en' => 'Super soft and huggable teddy bear, 18 inches tall, perfect gift for kids of all ages.', 'ar' => 'دب ناعم جداً للاحتضان، طوله 18 بوصة، هدية مثالية للأطفال من جميع الأعمار.'], 'price' => 19.99, 'compare_price' => 29.99, 'keywords' => ['teddy bear', 'stuffed animal', 'plush']],
            ['name' => ['en' => 'Kids Art & Craft Supply Set', 'ar' => 'مجموعة لوازم الفنون والحرف للأطفال'], 'description' => ['en' => 'Complete art kit with 140+ pieces: paints, brushes, colored pencils, and more.', 'ar' => 'طقم فني كامل مع أكثر من 140 قطعة: ألوان وفرش وأقلام ملونة والمزيد.'], 'price' => 29.99, 'compare_price' => 44.99, 'keywords' => ['art kit', 'crafts', 'paints']],
            ['name' => ['en' => 'Wooden Building Blocks Set 100pcs', 'ar' => 'مجموعة مكعبات خشبية 100 قطعة'], 'description' => ['en' => 'Classic wooden building blocks in natural colors, safe and durable for toddlers.', 'ar' => 'مكعبات خشبية كلاسيكية بألوان طبيعية، آمنة ومتينة للأطفال الصغار.'], 'price' => 22.99, 'compare_price' => 32.99, 'keywords' => ['wooden blocks', 'toddler', 'educational']],
            ['name' => ['en' => 'Outdoor Bubble Machine', 'ar' => 'آلة فقاعات خارجية'], 'description' => ['en' => 'Electric bubble machine produces thousands of bubbles per minute, fun for all kids.', 'ar' => 'آلة فقاعات كهربائية تنتج آلاف الفقاعات في الدقيقة، متعة لجميع الأطفال.'], 'price' => 16.99, 'compare_price' => 24.99, 'keywords' => ['bubbles', 'outdoor', 'fun']],
            ['name' => ['en' => 'Kids Walkie Talkie Set', 'ar' => 'جهاز اتصال لاسلكي للأطفال'], 'description' => ['en' => 'Long-range walkie talkies for kids, 2-pack with belt clips and clear audio.', 'ar' => 'أجهزة اتصال لاسلكية بعيدة المدى للأطفال، مجموعة من 2 مع مشابك حزام وصوت واضح.'], 'price' => 27.99, 'compare_price' => 39.99, 'keywords' => ['walkie talkie', 'communication', 'kids']],
            ['name' => ['en' => 'Jigsaw Puzzle 500 Pieces', 'ar' => 'أحجية بانوراما 500 قطعة'], 'description' => ['en' => 'Beautiful landscape jigsaw puzzle, 500 pieces for ages 8+, fun solo or family activity.', 'ar' => 'أحجية بانوراما جميلة من 500 قطعة لمن هم 8 سنوات فأكثر، نشاط ممتع بمفردك أو مع العائلة.'], 'price' => 14.99, 'compare_price' => 22.99, 'keywords' => ['puzzle', 'jigsaw', 'family']],
            ['name' => ['en' => 'Toy Kitchen Playset', 'ar' => 'مجموعة لعب المطبخ'], 'description' => ['en' => 'Realistic toy kitchen with pretend food, cookware, and appliances for kids 3+.', 'ar' => 'مطبخ لعبة واقعي مع طعام وهمي وأواني طهي وأجهزة للأطفال 3 سنوات فأكثر.'], 'price' => 59.99, 'compare_price' => 84.99, 'keywords' => ['kitchen', 'pretend play', 'cooking']],
            ['name' => ['en' => 'Scooter for Kids Adjustable', 'ar' => 'سكوتر للأطفال قابل للتعديل'], 'description' => ['en' => 'Lightweight adjustable scooter for kids 5-12 years, with LED light-up wheels.', 'ar' => 'سكوتر خفيف الوزن قابل للتعديل للأطفال 5-12 سنة، مع عجلات LED مضيئة.'], 'price' => 64.99, 'compare_price' => 89.99, 'keywords' => ['scooter', 'outdoor', 'kids']],
            ['name' => ['en' => 'Magnetic Drawing Board', 'ar' => 'لوح رسم مغناطيسي'], 'description' => ['en' => 'Mess-free magnetic drawing board for toddlers, erasable and reusable.', 'ar' => 'لوح رسم مغناطيسي بدون فوضى للأطفال الصغار، قابل للمسح وإعادة الاستخدام.'], 'price' => 18.99, 'compare_price' => 26.99, 'keywords' => ['drawing board', 'magnetic', 'toddler']],
        ];
    }
}
