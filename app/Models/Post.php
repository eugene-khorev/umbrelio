<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    public $timestamps = false;
    
    protected $attributes = array(
        'rating_total' => 0,
        'rating_count' => 0,
        'rating' => 0,
    );

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
