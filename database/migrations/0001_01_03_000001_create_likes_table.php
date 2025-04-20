<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                // 確保一個用戶只能喜歡一篇文章一次
                $table->unique(['user_id', 'post_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};