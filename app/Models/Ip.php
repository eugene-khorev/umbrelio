<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip',
    ];
    
    /**
     * Get the authors for the IP.
     */
    public function authors()
    {
        return $this->belongsToMany('App\Models\Author');
    }
}
