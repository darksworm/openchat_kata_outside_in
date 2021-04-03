<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->uuid('user_id')
                ->primary();

            $table->timestamps();

            $table->string('username', 128)
                ->unique();

            $table->string('password', 60);

            $table->longText('about');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
}
