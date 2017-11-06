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
        'ip',
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
    
    /**
     * Generates computed rating property
     * @return type
     */
    public function getRatingAttribute(): float
    {
        return $this->rating_count
                ? round($this->rating_total / $this->rating_count, 1)
                : 0;
    }
    
    /**
     * Increment rating fields and return new average rating
     * @param int $id
     * @param int $rating
     * @return type
     */
    public static function incrementRatings(int $id, int $rating)
    {
        static::where('id', $id)
            ->update([
                'rating_total' => \DB::raw('rating_total + ' .  $rating),
                'rating_count' => \DB::raw('rating_count + 1'),
            ]);
        $post = Post::find($id);
        return $post->rating;
    }
}
