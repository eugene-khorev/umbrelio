<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'ip',
    ];
    
    /**
     * Get the authors for the IP.
     */
    public function authors()
    {
        return $this->belongsTo('App\Models\Author');
    }
}
