<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = ['article_id', 'media_path', 'type'];

    public function media()
    {
        return $this->hasMany(SliderMedia::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}

