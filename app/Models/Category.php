<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function getBadgeColorAttribute()
    {

        $colors = [
            'lifestyle' => 'bg-gradient-to-r from-sky-100 to-sky-200 text-sky-800',
            'music' => 'bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800',
            'sport' => 'bg-gradient-to-r from-violet-100 to-violet-200 text-violet-800',
            'knowledge' => 'bg-gradient-to-r from-pink-100 to-pink-200 text-pink-800',
            'other' => 'bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800',
        ];

        return $colors[$this->name] ?? $colors['Other'];
    }

}
