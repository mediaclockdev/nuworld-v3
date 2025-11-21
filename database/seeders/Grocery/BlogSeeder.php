<?php
namespace Database\Seeders\Grocery;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Blog;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::disableForeignKeyConstraints();

        Post::truncate();
        Blog::truncate();

        Schema::enableForeignKeyConstraints();

        // -----------------------------------------------------------------
        // 1. GROCERY CATEGORIES (inserted into the `posts` table)
        // -----------------------------------------------------------------
        $categories = [
            [
                'title'       => 'Fresh Produce',
                'content'     => 'Discover the freshest fruits, vegetables, and herbs straight from local farms.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Dairy & Eggs',
                'content'     => 'Premium milk, cheese, yogurt, and farm-fresh eggs for your daily needs.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Pantry Staples',
                'content'     => 'Rice, pasta, oils, spices, and everything you need to stock your pantry.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Beverages',
                'content'     => 'Juices, teas, coffee, soft drinks, and healthy hydration options.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Snacks & Sweets',
                'content'     => 'Chips, cookies, chocolates, and healthier snack alternatives.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Frozen Foods',
                'content'     => 'Ready-to-cook meals, ice cream, frozen veggies, and more.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Meat & Seafood',
                'content'     => 'Fresh cuts of beef, chicken, pork, and sustainably sourced seafood.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Bakery',
                'content'     => 'Artisan breads, pastries, cakes, and daily-baked goodies.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Health & Wellness',
                'content'     => 'Organic, gluten-free, vegan, and superfood products for a healthier lifestyle.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'International Foods',
                'content'     => 'Ingredients and ready meals from Asian, Mediterranean, Mexican, and other cuisines.',
                'status'      => 1,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $cat['slug'] = Str::slug($cat['title']);
            $id = DB::table('posts')->insertGetId($cat);
            $categoryIds[] = $id;
        }

        // -----------------------------------------------------------------
        // 2. GROCERY BLOG POSTS (inserted into the `blogs` table)
        // -----------------------------------------------------------------
        $blogs = [
          // Fresh Produce (0)
          [
              'title'            => 'Top 10 Seasonal Fruits for 2025',
              'slug'             => Str::slug('Top 10 Seasonal Fruits for 2025'),
              'post_id'          => $categoryIds[0],
              'image'            => 'seasonal-fruits-2025.webp',
              'short_description'=> 'Must-try fruits that are at their peak this year.',
              'long_description' => 'From juicy mangoes to crisp apples, 2025 brings a vibrant variety of seasonal fruits that not only please your taste buds but also provide incredible nutritional benefits. This guide explores the top 10 must-try fruits of the year, including their unique flavors, best times to buy, and creative recipe ideas for smoothies, salads, and desserts. Learn how to pick, store, and enjoy each fruit at its freshest, and discover why seasonal eating is the healthiest and most sustainable choice for you and your family.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],
          [
              'title'            => 'How to Pick the Ripest Avocado',
              'slug'             => Str::slug('How to Pick the Ripest Avocado'),
              'post_id'          => $categoryIds[0],
              'image'            => 'avocado-guide.webp',
              'short_description'=> 'Never buy an under-ripe avocado again.',
              'long_description' => 'Selecting the perfect avocado can be tricky, but once you know the signs, it becomes second nature. This article teaches you how to use color, texture, and touch to identify ripeness — from the gentle squeeze test to checking under the stem. You’ll also learn how to speed up ripening naturally at home and the best storage techniques to keep your avocados fresh longer. Whether you’re making guacamole or slicing it for toast, these simple tricks will ensure you always get creamy, delicious results.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],

          // Dairy & Eggs (1)
          [
              'title'            => 'Choosing the Best Milk for Your Family',
              'slug'             => Str::slug('Choosing the Best Milk for Your Family'),
              'post_id'          => $categoryIds[1],
              'image'            => 'milk-types.webp',
              'short_description'=> 'Whole, skim, almond, oat – which one?',
              'long_description' => 'With so many milk options available today, choosing the right one can be overwhelming. This post breaks down the differences between dairy and plant-based milks such as almond, oat, soy, and coconut. You’ll discover how each type compares in terms of nutrition, taste, and environmental impact, as well as which milk works best for cooking, coffee, or cereal. Whether you’re lactose intolerant, vegan, or simply health-conscious, this guide helps you make the best choice for your family’s needs.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],
          [
              'title'            => 'Farm-Fresh vs. Store-Bought Eggs',
              'slug'             => Str::slug('Farm-Fresh vs Store-Bought Eggs'),
              'post_id'          => $categoryIds[1],
              'image'            => 'egg-comparison.webp',
              'short_description'=> 'What’s the real difference?',
              'long_description' => 'Eggs may look the same on the outside, but where they come from can make a world of difference. This article compares farm-fresh and store-bought eggs in terms of taste, texture, color, and nutritional content. Learn how egg labels like “cage-free,” “free-range,” and “pasture-raised” actually differ, and what to consider when making your purchase. You’ll also get insights into sustainable farming practices and how to support ethical egg production without compromising quality.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],

          // Pantry Staples (2)
          [
              'title'            => 'Essential Spices Every Kitchen Needs',
              'slug'             => Str::slug('Essential Spices Every Kitchen Needs'),
              'post_id'          => $categoryIds[2],
              'image'            => 'seasonal-fruits-2025.webp',
              'short_description'=> 'Build a flavor-packed pantry.',
              'long_description' => 'A well-stocked spice rack is the secret to transforming ordinary meals into extraordinary dishes. In this post, we list essential spices every home cook should have — from cumin and paprika to cinnamon and turmeric — and share tips on how to use them for maximum flavor. You’ll also learn about proper storage, shelf life, and how to create your own spice blends for everything from curries to grilled meats. Bring life and aroma to your kitchen with these timeless staples.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],
          [
              'title'            => 'How to Store Rice & Grains Long-Term',
              'slug'             => Str::slug('How to Store Rice & Grains Long-Term'),
              'post_id'          => $categoryIds[2],
              'image'            => 'grain-storage.webp',
              'short_description'=> 'Keep pantry staples fresh for months.',
              'long_description' => 'Proper storage of rice and grains can prevent spoilage, pests, and loss of nutrition. This guide covers practical long-term storage methods such as using airtight containers, maintaining ideal temperature and humidity, and incorporating natural repellents like bay leaves. You’ll also learn how to identify signs of spoilage and ways to revive older grains for safe consumption. Preserve freshness, flavor, and food safety with these tried-and-tested pantry management tips.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],

          // Snacks & Sweets (4)
          [
              'title'            => 'Guilt-Free Snack Swaps',
              'slug'             => Str::slug('Guilt-Free Snack Swaps'),
              'post_id'          => $categoryIds[4],
              'image'            => 'healthy-snacks.webp',
              'short_description'=> 'Swap chips for smarter bites.',
              'long_description' => 'Snacking doesn’t have to derail your healthy eating goals. This article explores smart alternatives to popular junk foods — like replacing potato chips with roasted chickpeas, or candy bars with dark chocolate and nuts. You’ll discover delicious, nutrient-packed snack ideas that curb cravings without adding excess sugar, salt, or calories. With these simple swaps, you can enjoy snacking guilt-free while fueling your body with wholesome goodness every day.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],
          [
              'title'            => 'Homemade Energy Bars in 15 Minutes',
              'slug'             => Str::slug('Homemade Energy Bars in 15 Minutes'),
              'post_id'          => $categoryIds[4],
              'image'            => 'energy-bars.webp',
              'short_description'=> 'Quick, no-bake recipe.',
              'long_description' => 'Busy schedules call for easy, nutritious snacks you can take anywhere. In this post, learn how to make your own no-bake energy bars using oats, nuts, seeds, dates, and honey — all in under 15 minutes. You’ll get creative flavor variations like chocolate-peanut butter or coconut-almond, along with tips on storage and packaging for meal prep. Perfect for breakfast on the go or a quick pre-workout boost, these bars are both tasty and energizing.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],

          // Bakery (7)
          [
              'title'            => 'Sourdough Starter From Scratch',
              'slug'             => Str::slug('Sourdough Starter From Scratch'),
              'post_id'          => $categoryIds[7],
              'image'            => 'sourdough-starter.webp',
              'short_description'=> 'Your own bubbly starter in 7 days.',
              'long_description' => 'Baking sourdough bread at home starts with one magical ingredient — a lively starter. This detailed guide walks you through every step of creating your own sourdough starter from just flour and water. You’ll learn how to feed it daily, recognize signs of fermentation, and troubleshoot common issues like mold or odor. Once your starter is ready, you’ll be able to bake beautifully crusty, tangy loaves that rival artisan bakeries, using nothing but natural wild yeast.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],
          [
              'title'            => 'Gluten-Free Baking Tips',
              'slug'             => Str::slug('Gluten-Free Baking Tips'),
              'post_id'          => $categoryIds[7],
              'image'            => 'gluten-free-baking.webp',
              'short_description'=> 'Fluffy results without wheat.',
              'long_description' => 'Gluten-free baking doesn’t mean sacrificing texture or flavor. This article shares professional tips on selecting the right flour blends, using binding agents like xanthan gum, and balancing moisture for soft, fluffy results. You’ll also discover how to adapt classic recipes to gluten-free versions without losing their charm. Whether you’re baking bread, cookies, or cakes, these easy-to-follow techniques ensure consistent success every time.',
              'status'           => 1,
              'published_at'     => now(),
              'created_by'       => 1,
              'updated_by'       => 1,
              'created_at'       => now(),
              'updated_at'       => now(),
          ],
      ];


        foreach ($blogs as $blog) {
            DB::table('blogs')->insert($blog);
        }

        $this->uploadBannerImages();
    }

    /**
     * Copy placeholder images from `public/SeederImages/Grocery/blogs`
     * into `storage/app/public/uploads/blogs`.
     */
    private function uploadBannerImages()
    {
        $relativePath    = 'uploads/blogs';
        $disk            = Storage::disk('public');
        $destinationPath = storage_path("app/public/{$relativePath}");
        $sourcePath      = public_path('SeederImages/Grocery/blogs');

        if (File::exists($destinationPath)) {
            File::deleteDirectory($destinationPath);
        }
        $disk->makeDirectory($relativePath);

        if (File::exists($sourcePath)) {
            foreach (File::files($sourcePath) as $file) {
                File::copy(
                    $file->getPathname(),
                    "{$destinationPath}/{$file->getFilename()}"
                );
            }
        } else {
            throw new \Exception("Source path does not exist: {$sourcePath}");
        }
    }
}
