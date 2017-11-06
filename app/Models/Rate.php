<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    
    protected $primaryKey = 'post_id';
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $attributes = array(
        'total' => 0,
        'num' => 0,
    );
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
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
