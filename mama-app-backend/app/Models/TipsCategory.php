<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipsCategory extends Model
{
    protected $table = 'tips_category';
    
    protected $fillable = [
        'name',
        'contents',
        'image',
        'tip_id'
    ];

    protected $appends = ['image_url'];

    public function tip()
    {
        return $this->belongsTo(MamaTip::class, 'tip_id');
    }

    /**
     * Get the full URL for the image
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('categories/default-category.png');
        }
        return asset($this->image);
    }

    /**
     * Override the image getter to return the original value
     */
    public function getImageAttribute($value)
    {
        return $value ?: 'categories/default-category.png';
    }

    /**
     * Override the image setter to store the full path
     */
    public function setImageAttribute($value)
    {
        $this->attributes['image'] = $value;
    }
} 