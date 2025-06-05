<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('email')->index()->unique();
            $table->string('password');
            $table->integer('status')->default(0);
            $table->foreignId('role_id')->nullable()->constrained('role')->onDelete('no action');
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('regional')->nullable();
            $table->text('verify_token')->nullable();
            $table->string('reset_token')->nullable();
            $table->text('forgot_token')->nullable();
            $table->string('anti_phising_code')->nullable();
            $table->integer('anti_phising')->default(0);
            $table->integer('two_factor_authentication')->default(0);
            $table->integer('newsletter')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('user');
    }
}
