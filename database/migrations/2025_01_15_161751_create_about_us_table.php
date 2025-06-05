<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('sort')->nullable();

            //seo
            $table->string('keyword_id')->nullable();
            $table->string('keyword_en')->nullable();
            $table->string('meta_title_id')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_description_id')->nullable();
            $table->string('meta_description_en')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('about_us');
    }
}
