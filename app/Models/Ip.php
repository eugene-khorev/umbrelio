<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    
    protected $primaryKey = 'ip';
    
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
     * Find an existing or creates a new IP record
     * @param string $addr
     * @return \App\Models\Ip
     */
    public static function findOrCreateByIp(string $addr): Ip
    {
        $ip = static::firstOrNew([ 'ip' => $addr ]);
        $ip->save();
        return $ip;
    }
    
    /**
     * Adds login for specified IP if it is not yet exists
     * @param string $addr
     * @param string $login
     * @return int
     */
    public static function updateLoginList(string $addr, string $login): int
    {
        return \DB::statement("
                UPDATE ips SET logins = logins || ARRAY[:login] 
                WHERE ip = :ip AND array_position(logins, :login) IS NULL
                ", ['login' => $login, 'ip' => $addr]
            );
    }
    
    /**
     * Returns list of IPs and author's logins used by at least $sharedBy authors
     * @param int $sharedBy
     * @param string $delimiter
     * @param int $limit
     * @return type
     */
    public static function getSharedIpAuthors(int $sharedBy, string $delimiter, int $limit = null): Collection
    {
        $query = \DB::table('ips')
                ->select(\DB::raw("ip, array_to_string(logins, '{$delimiter}') AS authors"))
                ->whereRaw("array_length(logins, 1) >= {$sharedBy}")
                ->orderBy('ip');
        
        if (is_numeric($limit)) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
}
