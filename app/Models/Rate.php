<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total',
        'num',
    ];
    
    /**
     * Get the post that owns the rate.
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }
}
