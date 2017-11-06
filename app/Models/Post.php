<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    public $timestamps = false;
    
    protected $attributes = array(
        'rating_total' => 0,
        'rating_count' => 0,
    );

    protected $appends = ['rating'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'title',
        'content',
        'rating_total',
        'rating_count',
    ];
    
    /**
     * Get the author that owns the post.
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }
    
    public function getRatingAttribute()
    {
        return $this->rating_count
                ? round($this->rating_total / $this->rating_count, 1)
                : 0;
    }    
}
