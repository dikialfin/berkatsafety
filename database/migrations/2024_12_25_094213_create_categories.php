<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->string('logo')->nullable();
            $table->integer('parent_id')->default(0);

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
        Schema::dropIfExists('categories');
    }
}
