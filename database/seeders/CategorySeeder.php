<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Health', 'slug' => 'health'],
            ['name' => 'Entertainment', 'slug' => 'entertainment'],
            ['name' => 'Science', 'slug' => 'science'],
            ['name' => 'World', 'slug' => 'world'],
            ['name' => 'Politics', 'slug' => 'politics'],
            ['name' => 'Education', 'slug' => 'education'],
            ['name' => 'Environment', 'slug' => 'environment'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle'],
            ['name' => 'Travel', 'slug' => 'travel'],
            ['name' => 'Food', 'slug' => 'food'],
            ['name' => 'Opinion', 'slug' => 'opinion'],
            ['name' => 'Economy', 'slug' => 'economy'],
            ['name' => 'Finance', 'slug' => 'finance'],
            ['name' => 'Automotive', 'slug' => 'automotive'],
            ['name' => 'Real Estate', 'slug' => 'real-estate'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Culture', 'slug' => 'culture'],
            ['name' => 'History', 'slug' => 'history'],
            ['name' => 'Religion', 'slug' => 'religion'],
            ['name' => 'Art', 'slug' => 'art'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Technology Trends', 'slug' => 'technology-trends'],
            ['name' => 'Movies', 'slug' => 'movies'],
            ['name' => 'Music', 'slug' => 'music'],
            ['name' => 'Television', 'slug' => 'television'],
            ['name' => 'Gadgets', 'slug' => 'gadgets'],
            ['name' => 'Startups', 'slug' => 'startups'],
            ['name' => 'Gaming', 'slug' => 'gaming'],
            ['name' => 'Social Media', 'slug' => 'social-media'],
            ['name' => 'Weather', 'slug' => 'weather'],
            ['name' => 'Space', 'slug' => 'space'],
            ['name' => 'Animals', 'slug' => 'animals'],
            ['name' => 'Health and Wellness', 'slug' => 'health-and-wellness'],
            ['name' => 'Fitness', 'slug' => 'fitness'],
            ['name' => 'Nutrition', 'slug' => 'nutrition'],
            ['name' => 'Parenting', 'slug' => 'parenting'],
            ['name' => 'Beauty', 'slug' => 'beauty'],
            ['name' => 'DIY', 'slug' => 'diy'],
            ['name' => 'Motorsports', 'slug' => 'motorsports'],
            ['name' => 'Crime', 'slug' => 'crime'],
            ['name' => 'National', 'slug' => 'national'],
            ['name' => 'Local', 'slug' => 'local'],
            ['name' => 'Travel Guides', 'slug' => 'travel-guides'],
            ['name' => 'Recipes', 'slug' => 'recipes'],
            ['name' => 'Mental Health', 'slug' => 'mental-health'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
