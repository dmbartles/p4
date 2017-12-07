<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('post_id');
            $table->timestamps();
            $table->string('user_name', 25);
            $table->string('topic');
            $table->string('subtopic');
            $table->text('post_text');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
