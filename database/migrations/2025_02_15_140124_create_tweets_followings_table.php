<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tweets_followings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->index('user_id');
            $table->foreignId('tweet_id')->constrained('tweets')->onDelete('cascade');
            $table->foreignId('tweet_autor_id')->constrained('users')->onDelete('cascade');
            $table->string('tweet_autor_name', 50);
            $table->string('content', 280);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweets_followings');
    }
};
