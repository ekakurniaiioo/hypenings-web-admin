<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderMedia extends Model
{
    use HasFactory;

    protected $table = 'slider_media';

    protected $fillable = [
        'slider_id',
        'file_path',
        'media_type',
    ];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }
}


