<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsArticleSliderToArticlesTable extends Migration
{

public function up()
{
    Schema::table('articles', function (Blueprint $table) {
        $table->boolean('is_article_slider')->default(false)->after('is_shorts');
    });
}

public function down()
{
    Schema::table('articles', function (Blueprint $table) {
        $table->dropColumn('is_article_slider');
    });
}

}
