<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->uuid('post_id')
                ->primary();
            $table->uuid('user_id');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('user')
                ->onDelete('cascade');

            $table->timestamps();

            $table->longText('text');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post');
    }
}
