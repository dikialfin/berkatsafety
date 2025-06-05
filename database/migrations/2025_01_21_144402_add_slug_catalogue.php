<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugCatalogue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogue', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('title');
            $table->string('keyword_id')->nullable();
            $table->string('keyword_en')->nullable();
            $table->string('meta_title_id')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_description_id')->nullable();
            $table->string('meta_description_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogue', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('keyword_id');
            $table->dropColumn('keyword_en');
            $table->dropColumn('meta_title_id');
            $table->dropColumn('meta_title_en');
            $table->dropColumn('meta_description_id');
            $table->dropColumn('meta_description_en');
        });
    }
}
