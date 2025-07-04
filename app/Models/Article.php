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
    'published_at',
    'category_id',
];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
