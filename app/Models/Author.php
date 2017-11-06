<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login',
    ];
    
    /**
     * Get the posts for the author.
     */
    public function posts()
    {
        return $this->hasMany('App\Model\Post');
    }
    
    /**
     * Find an existing or creates a new author record
     * @param string $login
     * @return \App\Models\Author
     */
    public static function findOrCreateByLogin(string $login): Author
    {
        $author = static::firstOrNew([ 'login' => $login ]);
        $author->save();
        return $author;
    }
}
