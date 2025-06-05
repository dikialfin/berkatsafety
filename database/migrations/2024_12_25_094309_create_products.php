<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('admin_id');

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
        Schema::dropIfExists('products');
    }
}
