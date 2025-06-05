<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsCatalogueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands_catalogue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_id')->constrained('catalogue')->onDelete('no action');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('no action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands_catalogue');
    }
}
