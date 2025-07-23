<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsArticleSlidersToArticlesTable extends Migration
{
 
public function up(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->boolean('is_article_slider')->default(false)->after ('video_path');
    });
}

public function down(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->dropColumn('is_article_slider');
    });
}

}
