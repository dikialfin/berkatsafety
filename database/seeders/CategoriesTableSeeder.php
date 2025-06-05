<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data default
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => Str::slug('Electronics'),
                'description_id' => 'Kategori untuk produk elektronik.',
                'description_en' => 'Category for electronic products.',
                'logo' => url('images/brand/brand-1.png'),
                'parent_id' => null,
                'keyword_id' => 'elektronik, gadget, teknologi',
                'keyword_en' => 'electronics, gadgets, technology',
                'meta_title_id' => 'Judul Meta Electronics',
                'meta_title_en' => 'Meta Title Electronics',
                'meta_description_id' => 'Meta deskripsi untuk kategori elektronik.',
                'meta_description_en' => 'Meta description for electronics category.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Smartphones',
                'slug' => Str::slug('Smartphones'),
                'description_id' => 'Kategori untuk ponsel pintar.',
                'description_en' => 'Category for smartphones.',
                'logo' => url('images/brand/brand-2.png'),
                'parent_id' => 1,
                'keyword_id' => 'ponsel pintar, hp, gadget',
                'keyword_en' => 'smartphones, phones, gadgets',
                'meta_title_id' => 'Judul Meta Smartphones',
                'meta_title_en' => 'Meta Title Smartphones',
                'meta_description_id' => 'Meta deskripsi untuk kategori ponsel pintar.',
                'meta_description_en' => 'Meta description for smartphones category.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Laptops',
                'slug' => Str::slug('Laptops'),
                'description_id' => 'Kategori untuk laptop.',
                'description_en' => 'Category for laptops.',
                'logo' => url('images/brand/brand-3.png'),
                'parent_id' => 1,
                'keyword_id' => 'laptop, komputer, teknologi',
                'keyword_en' => 'laptops, computers, technology',
                'meta_title_id' => 'Judul Meta Laptops',
                'meta_title_en' => 'Meta Title Laptops',
                'meta_description_id' => 'Meta deskripsi untuk kategori laptop.',
                'meta_description_en' => 'Meta description for laptops category.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ];

        // Insert data ke database
        DB::table('categories')->insert($categories);
    }
}
