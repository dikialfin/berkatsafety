<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogs = [
            [
                'name' => 'The Future of Technology',
                'slug' => Str::slug('The Future of Technology'),
                'image' => url('images/brand/brand-1.png'),
                'description_id' => 'Blog tentang masa depan teknologi dan inovasi.',
                'description_en' => 'Blog about the future of technology and innovation.',
                'admin_id' => 1,
                'keyword_id' => 'teknologi, masa depan, inovasi',
                'keyword_en' => 'technology, future, innovation',
                'meta_title_id' => 'Judul Meta Masa Depan Teknologi',
                'meta_title_en' => 'Meta Title Future of Technology',
                'meta_description_id' => 'Meta deskripsi untuk blog tentang masa depan teknologi.',
                'meta_description_en' => 'Meta description for the blog about the future of technology.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Sustainable Living Tips',
                'slug' => Str::slug('Sustainable Living Tips'),
                'image' => url('images/brand/brand-2.png'),
                'description_id' => 'Tips hidup berkelanjutan untuk melindungi lingkungan.',
                'description_en' => 'Tips for sustainable living to protect the environment.',
                'admin_id' => 2,
                'keyword_id' => 'berkelanjutan, lingkungan, gaya hidup',
                'keyword_en' => 'sustainable, environment, lifestyle',
                'meta_title_id' => 'Judul Meta Hidup Berkelanjutan',
                'meta_title_en' => 'Meta Title Sustainable Living',
                'meta_description_id' => 'Meta deskripsi untuk blog tentang hidup berkelanjutan.',
                'meta_description_en' => 'Meta description for the blog about sustainable living.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Top Travel Destinations in 2025',
                'slug' => Str::slug('Top Travel Destinations in 2025'),
                'image' => url('images/brand/brand-1.png'),
                'description_id' => 'Destinasi wisata terbaik yang harus dikunjungi pada tahun 2025.',
                'description_en' => 'Top travel destinations to visit in 2025.',
                'admin_id' => 1,
                'keyword_id' => 'wisata, perjalanan, 2025',
                'keyword_en' => 'travel, destinations, 2025',
                'meta_title_id' => 'Judul Meta Destinasi Wisata 2025',
                'meta_title_en' => 'Meta Title Travel Destinations',
                'meta_description_id' => 'Meta deskripsi untuk blog tentang destinasi wisata terbaik tahun 2025',
                'meta_description_en' => 'Meta description for the blog about the best travel destinations in 2025.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ];

        DB::table('blogs')->insert($blogs);
    }
}
