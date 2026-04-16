<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    /**
     * Map of product slug keywords to Unsplash CDN image URLs.
     * Each key is a substring to match against the product slug.
     */
    private array $imageMap = [
        // Electronics & Technology
        'premium-smartphone'          => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80&auto=format&fit=crop',
        'gaming-laptop'               => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&q=80&auto=format&fit=crop',
        'wireless-bluetooth-headphone'=> 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80&auto=format&fit=crop',
        'smart-watch'                 => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80&auto=format&fit=crop',
        '4k-ultra-hd-tv'             => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=800&q=80&auto=format&fit=crop',
        'wireless-charging-pad'       => 'https://images.unsplash.com/photo-1591522811280-a8759970b03f?w=800&q=80&auto=format&fit=crop',
        'portable-power-bank'         => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=800&q=80&auto=format&fit=crop',
        'usb-c-hub'                   => 'https://images.unsplash.com/photo-1625842268584-8f3296236761?w=800&q=80&auto=format&fit=crop',
        'mechanical-gaming-keyboard'  => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&q=80&auto=format&fit=crop',
        'wireless-mouse'              => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&q=80&auto=format&fit=crop',

        // Fashion & Apparel
        'classic-denim-jeans'         => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=80&auto=format&fit=crop',
        'cotton-t-shirt'              => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&q=80&auto=format&fit=crop',
        'leather-jacket'              => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&q=80&auto=format&fit=crop',
        'running-sneakers'            => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80&auto=format&fit=crop',
        'designer-handbag'            => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&q=80&auto=format&fit=crop',
        'silk-scarf'                  => 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=800&q=80&auto=format&fit=crop',
        'wool-winter-coat'            => 'https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=800&q=80&auto=format&fit=crop',
        'casual-dress-summer'         => 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=800&q=80&auto=format&fit=crop',
        'leather-belt'                => 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=800&q=80&auto=format&fit=crop',
        'sports-cap'                  => 'https://images.unsplash.com/photo-1533827432537-70133748f5c8?w=800&q=80&auto=format&fit=crop',

        // Food & Beverages
        'organic-fresh-strawberries'  => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=800&q=80&auto=format&fit=crop',
        'premium-arabica-coffee'      => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80&auto=format&fit=crop',
        'fresh-whole-milk'            => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=800&q=80&auto=format&fit=crop',
        'artisan-sourdough-bread'     => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&q=80&auto=format&fit=crop',
        'organic-free-range-eggs'     => 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=800&q=80&auto=format&fit=crop',
        'fresh-salmon-fillet'         => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&q=80&auto=format&fit=crop',
        'extra-virgin-olive-oil'      => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=800&q=80&auto=format&fit=crop',
        'organic-honey-raw'           => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80&auto=format&fit=crop',
        'fresh-spinach'               => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=800&q=80&auto=format&fit=crop',
        'premium-dark-chocolate'      => 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=800&q=80&auto=format&fit=crop',

        // Home & Garden
        'modern-sofa'                 => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80&auto=format&fit=crop',
        'queen-size-memory-foam-mattress' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80&auto=format&fit=crop',
        'led-floor-lamp'              => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80&auto=format&fit=crop',
        'garden-tool-set'             => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80&auto=format&fit=crop',
        'stainless-steel-cookware'    => 'https://images.unsplash.com/photo-1585515320310-259814833e62?w=800&q=80&auto=format&fit=crop',
        'indoor-plant-collection'     => 'https://images.unsplash.com/photo-1463320726281-696a485928c7?w=800&q=80&auto=format&fit=crop',
        'luxury-bedding-set'          => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=800&q=80&auto=format&fit=crop',
        'wall-art-canvas'             => 'https://picsum.photos/seed/wall-art-canvas/800/600',
        'garden-hose'                 => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80&auto=format&fit=crop',
        'storage-baskets'             => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?w=800&q=80&auto=format&fit=crop',

        // Health & Beauty
        'vitamin-c-serum'             => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=800&q=80&auto=format&fit=crop',
        'hydrating-face-moisturizer'  => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=800&q=80&auto=format&fit=crop',
        'matte-lipstick-set'          => 'https://images.unsplash.com/photo-1515688594390-b649af70d282?w=800&q=80&auto=format&fit=crop',
        'professional-hair-dryer'     => 'https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=800&q=80&auto=format&fit=crop',
        'luxury-perfume'              => 'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=800&q=80&auto=format&fit=crop',
        'multivitamin-supplement'     => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&q=80&auto=format&fit=crop',
        'sunscreen-spf'               => 'https://images.unsplash.com/photo-1576426863848-c21f53c60b19?w=800&q=80&auto=format&fit=crop',
        'face-cleansing-brush'        => 'https://images.unsplash.com/photo-1563804447971-6e113ab80713?w=800&q=80&auto=format&fit=crop',
        'hair-shampoo-conditioner'    => 'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&q=80&auto=format&fit=crop',
        'nail-polish-set'             => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80&auto=format&fit=crop',

        // Sports & Outdoors
        'professional-football'       => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&q=80&auto=format&fit=crop',
        'yoga-mat-premium'            => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&q=80&auto=format&fit=crop',
        'dumbbell-set'                => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80&auto=format&fit=crop',
        'camping-tent'                => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&q=80&auto=format&fit=crop',
        'running-shoes-athletic'      => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80&auto=format&fit=crop',
        'basketball-official'         => 'https://images.unsplash.com/photo-1519861531473-9200262188bf?w=800&q=80&auto=format&fit=crop',
        'hiking-backpack'             => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=80&auto=format&fit=crop',
        'resistance-bands-set'        => 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=800&q=80&auto=format&fit=crop',
        'tennis-racket'               => 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=800&q=80&auto=format&fit=crop',
        'swimming-goggles'            => 'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?w=800&q=80&auto=format&fit=crop',

        // Books & Media
        'bestselling-fiction-novel'   => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800&q=80&auto=format&fit=crop',
        'programming-guide-advanced'  => 'https://images.unsplash.com/photo-1555099962-4199c345e5dd?w=800&q=80&auto=format&fit=crop',
        'childrens-storybook'         => 'https://images.unsplash.com/photo-1519340241574-2cec6aef0c01?w=800&q=80&auto=format&fit=crop',
        'music-album-cd'              => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&q=80&auto=format&fit=crop',
        'movie-collection-blu-ray'    => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&q=80&auto=format&fit=crop',
        'art-supplies-sketchbook'     => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&q=80&auto=format&fit=crop',
        'language-learning-course'    => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&q=80&auto=format&fit=crop',
        'magazine-subscription'       => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=800&q=80&auto=format&fit=crop',
        'vinyl-record-classic'        => 'https://images.unsplash.com/photo-1415201364774-f6f0bb35f28f?w=800&q=80&auto=format&fit=crop',
        'educational-puzzle-set'      => 'https://images.unsplash.com/photo-1611996575749-79a3a250f948?w=800&q=80&auto=format&fit=crop',

        // Automotive & Parts
        'engine-oil-premium'          => 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=800&q=80&auto=format&fit=crop',
        'brake-pads-set'              => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=800&q=80&auto=format&fit=crop',
        'car-battery'                 => 'https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?w=800&q=80&auto=format&fit=crop',
        'all-season-tires'            => 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=800&q=80&auto=format&fit=crop',
        'car-air-filter'              => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&q=80&auto=format&fit=crop',
        'car-wax-polish'              => 'https://images.unsplash.com/photo-1601362840469-51e4d8d58785?w=800&q=80&auto=format&fit=crop',
        'led-headlight-bulbs'         => 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=800&q=80&auto=format&fit=crop',
        'car-floor-mats'              => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800&q=80&auto=format&fit=crop',
        'spark-plugs-set'             => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=800&q=80&auto=format&fit=crop',
        'car-phone-mount'             => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=800&q=80&auto=format&fit=crop',

        // Toys & Games (ToysCategorySeeder)
        'lego-classic-creative-bricks'  => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=800&q=80&auto=format&fit=crop',
        'remote-control-racing-car'     => 'https://images.unsplash.com/photo-1581235720704-06d3acfcb36f?w=800&q=80&auto=format&fit=crop',
        'monopoly-classic-board-game'   => 'https://images.unsplash.com/photo-1611996575749-79a3a250f948?w=800&q=80&auto=format&fit=crop',
        'kids-science-experiment-kit'   => 'https://images.unsplash.com/photo-1509228627152-72ae9ae6848d?w=800&q=80&auto=format&fit=crop',
        'stuffed-animal-teddy-bear'     => 'https://images.unsplash.com/photo-1558591710-4b4a1ae0f04d?w=800&q=80&auto=format&fit=crop',
        'kids-art-craft-supply-set'     => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&q=80&auto=format&fit=crop',
        'wooden-building-blocks-set'    => 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=800&q=80&auto=format&fit=crop',
        'outdoor-bubble-machine'        => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80&auto=format&fit=crop',
        'kids-walkie-talkie-set'        => 'https://images.unsplash.com/photo-1535615615570-3b839f4359be?w=800&q=80&auto=format&fit=crop',
        'jigsaw-puzzle-500-pieces'      => 'https://images.unsplash.com/photo-1611996575749-79a3a250f948?w=800&q=80&auto=format&fit=crop',
        'toy-kitchen-playset'           => 'https://images.unsplash.com/photo-1558591710-4b4a1ae0f04d?w=800&q=80&auto=format&fit=crop',
        'scooter-for-kids'              => 'https://images.unsplash.com/photo-1583521214690-73421a1829a9?w=800&q=80&auto=format&fit=crop',
        'magnetic-drawing-board'        => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&q=80&auto=format&fit=crop',
    ];

    public function run(): void
    {
        $products = Product::all();
        $matched = 0;
        $skipped = 0;

        foreach ($products as $product) {
            // Skip if already has images
            if ($product->getMedia('product-images')->isNotEmpty()) {
                $skipped++;
                continue;
            }

            $imageUrl = $this->findImageForProduct($product->slug);

            if ($imageUrl === null) {
                $this->command->warn("No image found for: {$product->slug}");
                $skipped++;
                continue;
            }

            try {
                $product->addMediaFromUrl($imageUrl)
                    ->toMediaCollection('product-images');
                $matched++;
                $this->command->info("Added image to: {$product->slug}");
            } catch (\Exception $e) {
                $this->command->error("Failed for {$product->slug}: {$e->getMessage()}");
                $skipped++;
            }
        }

        $this->command->info("Done. Images added: {$matched}, Skipped/failed: {$skipped}.");
    }

    private function findImageForProduct(string $slug): ?string
    {
        foreach ($this->imageMap as $keyword => $url) {
            if (str_contains($slug, $keyword)) {
                return $url;
            }
        }

        // Fallback: try matching on individual slug segments
        $slugParts = explode('-', $slug);
        foreach ($this->imageMap as $keyword => $url) {
            $keywordParts = explode('-', $keyword);
            $commonParts = array_intersect($slugParts, $keywordParts);
            // Match if at least 2 meaningful slug words overlap
            if (count($commonParts) >= 2) {
                return $url;
            }
        }

        return null;
    }
}
