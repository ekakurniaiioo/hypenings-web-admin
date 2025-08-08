<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SliderMedia extends Model
{
    protected $table = 'slider_media';

    protected $fillable = ['slider_id', 'media_type', 'file_path'];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

}
