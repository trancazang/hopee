<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forum_post_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('post_id');('post_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('vote_type', ['upvote', 'downvote']);
            $table->timestamps();
        
            $table->unique(['post_id', 'user_id']);
            $table->foreign('post_id')->references('id')->on('forum_posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_post_votes');
    }
    
};
