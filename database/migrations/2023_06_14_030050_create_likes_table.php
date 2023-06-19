<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')
            //     ->references('id')
            //     ->on('User')
            //     ->onDelete('User');

            $table->unsignedBigInteger("object_id");
            $table->foreignId('type_id')->nullable()->constrained('type_like')->onDelete('cascade');
                
            // $table->unsignedBigInteger('type_id');
            // $table->foreign('type_id')
            //     ->references('id')
            //     ->on('type_likes')
            //     ->onDelete('type_likes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
