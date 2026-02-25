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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users")->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title', 255)->unique(); //post 1, Post 2
            $table->string('slug', 255)->unique(); //post-1, post-2
            $table->string('post_image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->enum('status',['draft','published','archived'])
            ->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->softDeletes();
            $table->index(['status','published_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
