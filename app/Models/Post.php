<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
    ];
    
    /**
     * Get the author that owns the post.
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }
    
    /**
     * Get the rate for the post.
     */
    public function rate()
    {
        return $this->haseOne('App\Models\Rate');
    }
}
