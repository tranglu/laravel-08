<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();//tên bài hát
            $table->string('composer')->nullable();//tác giả
            $table->string('singer')->nullable();// ca sĩ thể hiện
            $table->string('thumbnail')->nullable();//hình bài hát
            $table->text('lyric')->nullable();//lời bài hát
            $table->string('url')->nullable();//link bài hát
            $table->foreignid('user_id')->constrained('users');
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
        Schema::dropIfExists('songs');
    }
}
