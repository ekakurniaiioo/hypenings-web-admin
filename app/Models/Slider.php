<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['article_id'];

    // Relasi ke slider_media
    public function sliderMedia()
    {
        return $this->hasMany(SliderMedia::class, 'slider_id');
    }

    // Relasi balik ke article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
