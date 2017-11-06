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
     * Get the IPs for the author.
     */
    public function ips()
    {
        return $this->hasMany('App\Models\Ip');
    }
}
