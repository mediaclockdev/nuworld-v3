<?php

namespace Database\Seeders\Fashion;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Storage, File};


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
        // Sample furniture-related categories (posts)
        $categories = [
            [
                'title' => 'Sofas and Sectionals',
                'content' => 'Explore a variety of sofas and sectional designs, from modern to classic, for your living room.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Dining Tables',
                'content' => 'Discover dining tables that suit every style, from rustic to contemporary, for your dining space.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Eco-Friendly Furniture',
                'content' => 'Sustainable furniture options made from reclaimed wood, bamboo, and recycled materials.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bedroom Furniture',
                'content' => 'Furniture solutions for bedrooms, including beds, nightstands, and storage options.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Office Furniture',
                'content' => 'Ergonomic and stylish furniture for home and office workspaces.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Outdoor Furniture',
                'content' => 'Durable and stylish furniture for patios, gardens, and outdoor spaces.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Coffee Tables',
                'content' => 'Stylish and functional coffee tables to enhance your living room.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Storage Solutions',
                'content' => 'Furniture with built-in storage to maximize space in your home.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Vintage Furniture',
                'content' => 'Timeless vintage and antique furniture pieces to add character to your home.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Upholstered Furniture',
                'content' => 'Explore upholstered furniture options, from leather to fabric, for comfort and style.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert categories and collect their IDs
        $categoryIds = [];
        foreach ($categories as $category) {
            $category['slug'] = Str::slug($category['title']);
            $categoryId = DB::table('posts')->insertGetId($category);
            $categoryIds[] = $categoryId;
        }

        // Sample furniture-related blogs
        $blogs = [
            // Sofas and Sectionals (Category 1)
            [
                'title' => 'Top 5 Sofa Trends for 2025',
                'slug' => Str::slug('Top 5 Sofa Trends for 2025'),
                'post_id' => $categoryIds[0],
                'image' => 'sofa-designs-2025.jpg',
                'short_description' => 'Discover the latest sofa designs for modern living rooms.',
                'long_description' => 'From modular sectionals to eco-friendly fabrics, explore the top 5 sofa trends for 2025 that combine style, comfort, and sustainability for contemporary homes.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'How to Choose the Perfect Sectional Sofa',
                'slug' => Str::slug('How to Choose the Perfect Sectional Sofa'),
                'post_id' => $categoryIds[0],
                'image' => 'sectional-sofa-guide.jpg',
                'short_description' => 'A guide to selecting the ideal sectional sofa for your space.',
                'long_description' => 'Sectional sofas offer versatility and comfort. This guide covers size, shape, and material considerations to help you choose the perfect sectional for your living room.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Dining Tables (Category 2)
            [
                'title' => 'Rustic vs. Modern Dining Tables: Which is Right for You?',
                'slug' => Str::slug('Rustic vs Modern Dining Tables'),
                'post_id' => $categoryIds[1],
                'image' => 'dining-table-styles.jpg',
                'short_description' => 'Compare rustic and modern dining tables for your home.',
                'long_description' => 'Rustic and modern dining tables each offer unique aesthetics. Learn the pros and cons of each style to find the perfect fit for your dining space.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Space-Saving Dining Tables for Small Homes',
                'slug' => Str::slug('Space-Saving Dining Tables for Small Homes'),
                'post_id' => $categoryIds[1],
                'image' => 'space-saving-dining.jpg',
                'short_description' => 'Compact dining tables for small spaces.',
                'long_description' => 'Small homes require smart solutions. Discover extendable and foldable dining tables that maximize space without compromising style or functionality.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Eco-Friendly Furniture (Category 3)
            [
                'title' => 'Why Choose Reclaimed Wood Furniture?',
                'slug' => Str::slug('Why Choose Reclaimed Wood Furniture'),
                'post_id' => $categoryIds[2],
                'image' => 'reclaimed-wood-furniture.jpg',
                'short_description' => 'Benefits of reclaimed wood for sustainable homes.',
                'long_description' => 'Reclaimed wood furniture is eco-friendly and stylish. Learn why it’s a great choice for sustainability and how it adds character to your home.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bamboo Furniture: A Sustainable Choice',
                'slug' => Str::slug('Bamboo Furniture A Sustainable Choice'),
                'post_id' => $categoryIds[2],
                'image' => 'bamboo-furniture.jpg',
                'short_description' => 'Explore bamboo furniture for eco-conscious living.',
                'long_description' => 'Bamboo is a renewable resource perfect for furniture. This article explores its benefits, durability, and stylish designs for sustainable home decor.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Bedroom Furniture (Category 4)
            [
                'title' => 'How to Choose the Perfect Bed Frame',
                'slug' => Str::slug('How to Choose the Perfect Bed Frame'),
                'post_id' => $categoryIds[3],
                'image' => 'bed-frame-guide.jpg',
                'short_description' => 'Find the ideal bed frame for your bedroom.',
                'long_description' => 'A bed frame sets the tone for your bedroom. This guide covers materials, styles, and sizes to help you choose the perfect frame for comfort and aesthetics.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Maximizing Bedroom Storage with Furniture',
                'slug' => Str::slug('Maximizing Bedroom Storage with Furniture'),
                'post_id' => $categoryIds[3],
                'image' => 'bedroom-storage.jpg',
                'short_description' => 'Smart furniture for bedroom organization.',
                'long_description' => 'Keep your bedroom clutter-free with storage beds, nightstands with drawers, and other furniture designed to maximize space and organization.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Office Furniture (Category 5)
            [
                'title' => 'Ergonomic Chairs for Home Offices',
                'slug' => Str::slug('Ergonomic Chairs for Home Offices'),
                'post_id' => $categoryIds[4],
                'image' => 'ergonomic-chairs.jpg',
                'short_description' => 'Improve productivity with ergonomic chairs.',
                'long_description' => 'Ergonomic chairs enhance comfort and posture in home offices. Learn how to choose the right chair for long hours of work and better health.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Stylish Desks for Modern Workspaces',
                'slug' => Str::slug('Stylish Desks for Modern Workspaces'),
                'post_id' => $categoryIds[4],
                'image' => 'modern-desks.jpg',
                'short_description' => 'Desks that blend style and functionality.',
                'long_description' => 'A stylish desk can transform your workspace. Explore modern designs with built-in storage and ergonomic features for a productive home office.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Outdoor Furniture (Category 6)
            [
                'title' => 'Weather-Resistant Outdoor Furniture for 2025',
                'slug' => Str::slug('Weather-Resistant Outdoor Furniture for 2025'),
                'post_id' => $categoryIds[5],
                'image' => 'outdoor-furniture-2025.jpg',
                'short_description' => 'Durable furniture for your patio or garden.',
                'long_description' => 'Discover weather-resistant outdoor furniture that combines durability with style, perfect for patios, gardens, and year-round outdoor living.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Creating an Outdoor Lounge with Furniture',
                'slug' => Str::slug('Creating an Outdoor Lounge with Furniture'),
                'post_id' => $categoryIds[5],
                'image' => 'outdoor-lounge.jpg',
                'short_description' => 'Build a cozy outdoor lounge area.',
                'long_description' => 'Transform your outdoor space into a relaxing lounge with comfortable seating, tables, and weatherproof accessories for entertaining or unwinding.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Coffee Tables (Category 7)
            [
                'title' => 'How to Style Your Coffee Table',
                'slug' => Str::slug('How to Style Your Coffee Table'),
                'post_id' => $categoryIds[6],
                'image' => 'coffee-table-styling.jpg',
                'short_description' => 'Tips for styling a chic coffee table.',
                'long_description' => 'A well-styled coffee table elevates your living room. Learn how to use decor, trays, and books to create a functional and beautiful centerpiece.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Choosing a Coffee Table for Small Spaces',
                'slug' => Str::slug('Choosing a Coffee Table for Small Spaces'),
                'post_id' => $categoryIds[6],
                'image' => 'small-coffee-tables.jpg',
                'short_description' => 'Compact coffee tables for small rooms.',
                'long_description' => 'Small living rooms need smart coffee table solutions. Explore compact, multifunctional designs that save space while adding style to your home.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Storage Solutions (Category 8)
            [
                'title' => 'Multifunctional Furniture for Storage',
                'slug' => Str::slug('Multifunctional Furniture for Storage'),
                'post_id' => $categoryIds[7],
                'image' => 'multifunctional-storage.jpg',
                'short_description' => 'Furniture that doubles as storage.',
                'long_description' => 'Maximize space with furniture like ottomans with storage, shelving units, and beds with built-in drawers, perfect for organized living.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Creative Storage Ideas for Small Homes',
                'slug' => Str::slug('Creative Storage Ideas for Small Homes'),
                'post_id' => $categoryIds[7],
                'image' => 'small-home-storage.jpg',
                'short_description' => 'Innovative storage furniture for small spaces.',
                'long_description' => 'Small homes benefit from creative storage solutions. Discover furniture like wall-mounted shelves and under-bed storage to keep your space tidy.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Vintage Furniture (Category 9)
            [
                'title' => 'Restoring Vintage Furniture: A Guide',
                'slug' => Str::slug('Restoring Vintage Furniture A Guide'),
                'post_id' => $categoryIds[8],
                'image' => 'vintage-restoration.jpg',
                'short_description' => 'Restore vintage furniture with care.',
                'long_description' => 'Vintage furniture adds character but may need restoration. This guide covers cleaning, repairing, and refinishing to preserve its beauty and value.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Mixing Vintage and Modern Furniture',
                'slug' => Str::slug('Mixing Vintage and Modern Furniture'),
                'post_id' => $categoryIds[8],
                'image' => 'vintage-modern-mix.jpg',
                'short_description' => 'Blend vintage and modern for a unique look.',
                'long_description' => 'Combining vintage and modern furniture creates an eclectic style. Learn how to balance antique pieces with contemporary designs for a cohesive home.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Upholstered Furniture (Category 10)
            [
                'title' => 'Leather vs. Fabric Upholstery: Pros and Cons',
                'slug' => Str::slug('Leather vs Fabric Upholstery'),
                'post_id' => $categoryIds[9],
                'image' => 'upholstery-comparison.jpg',
                'short_description' => 'Compare leather and fabric upholstery options.',
                'long_description' => 'Choosing between leather and fabric upholstery depends on style and practicality. This article breaks down their durability, maintenance, and aesthetic appeal.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'How to Clean Upholstered Furniture',
                'slug' => Str::slug('How to Clean Upholstered Furniture'),
                'post_id' => $categoryIds[9],
                'image' => 'upholstery-cleaning.jpg',
                'short_description' => 'Keep upholstered furniture looking fresh.',
                'long_description' => 'Upholstered furniture requires regular care. Learn cleaning techniques for leather, fabric, and microfiber to maintain their appearance and longevity.',
                'status' => 1,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert blogs
        foreach ($blogs as $blog) {
            DB::table('blogs')->insert($blog);
        }

        $this->uploadBannerImages();
    }

    private function uploadBannerImages()
    {
    $relativePath    = 'uploads/blogs';
    $disk            = Storage::disk('public');
    $destinationPath = storage_path("app/public/{$relativePath}");
    $sourcePath      = public_path('SeederImages/Furniture/blogs');

    if (File::exists($destinationPath)) {
      File::deleteDirectory($destinationPath);
    }
    $disk->makeDirectory($relativePath);
    // Copy files from source to destination
    if (File::exists($sourcePath)) {
      foreach (File::files($sourcePath) as $file) {
        File::copy($file->getPathname(), "{$destinationPath}/{$file->getFilename()}");
      }
    } else {
      throw new \Exception("Source path does not exist: {$sourcePath}");
    }
  }
}
