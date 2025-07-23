<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'video_path',
        'published_at',
        'category_id',
        'is_trending',
        'is_topic',
        'is_featured_slider',
        'is_shorts',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function slider()
    {
        return $this->hasOne(Slider::class);
    }
}
