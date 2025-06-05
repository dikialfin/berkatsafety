<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'name' => 'Brand One',
                'slug' => Str::slug('Brand One'),
                'description_id' => 'Deskripsi untuk Brand One dalam bahasa Indonesia.',
                'description_en' => 'Description for Brand One in English.',
                'logo' => url('images/brand/brand-1.png'),
                'banner' => url('images/brand/brand-1.png'),
                'keyword_id' => 'brand1, produk1, merek1',
                'keyword_en' => 'brand1, product1, trademark1',
                'meta_title_id' => 'Judul Meta Brand One',
                'meta_title_en' => 'Meta Title Brand One',
                'meta_description_id' => 'Meta deskripsi untuk Brand One.',
                'meta_description_en' => 'Meta description for Brand One.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Brand Two',
                'slug' => Str::slug('Brand Two'),
                'description_id' => 'Deskripsi untuk Brand Two dalam bahasa Indonesia.',
                'description_en' => 'Description for Brand Two in English.',
                'logo' => url('images/brand/brand-2.png'),
                'banner' => url('images/brand/brand-3.png'),
                'keyword_id' => 'brand2, produk2, merek2',
                'keyword_en' => 'brand2, product2, trademark2',
                'meta_title_id' => 'Judul Meta Brand Two',
                'meta_title_en' => 'Meta Title Brand Two',
                'meta_description_id' => 'Meta deskripsi untuk Brand Two.',
                'meta_description_en' => 'Meta description for Brand Two.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Brand Three',
                'slug' => Str::slug('Brand Three'),
                'description_id' => 'Deskripsi untuk Brand Three dalam bahasa Indonesia.',
                'description_en' => 'Description for Brand Three in English.',
                'logo' => url('images/brand/brand-3.png'),
                'banner' => url('images/brand/brand-3.png'),
                'keyword_id' => 'brand3, produk3, merek3',
                'keyword_en' => 'brand3, product3, trademark3',
                'meta_title_id' => 'Judul Meta Brand Three',
                'meta_title_en' => 'Meta Title Brand Three',
                'meta_description_id' => 'Meta deskripsi untuk Brand Three.',
                'meta_description_en' => 'Meta description for Brand Three.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}
