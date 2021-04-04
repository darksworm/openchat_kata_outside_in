<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowingTable extends Migration
{
    public function up()
    {
        Schema::create('following', function (Blueprint $table) {
            $columns = ['follower_id', 'followee_id'];

            foreach ($columns as $column) {
                $table->uuid($column);
                $table->foreign($column)
                    ->references('user_id')
                    ->on('user')
                    ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('following');
    }
}
