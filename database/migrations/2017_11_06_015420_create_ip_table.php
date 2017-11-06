<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ips', function (Blueprint $table) {
            $table->integer('author_id');
            $table->ipAddress('ip');
            
            $table->primary(['author_id', 'ip']);
        });
        
        \DB::statement('
            CREATE VIEW shared_ip_authors AS 
                SELECT 
                    r.ip,
                    r.author_id
                FROM ips r
                    LEFT JOIN ( 
                        SELECT ips.ip
                        FROM ips
                        GROUP BY ips.ip
                        HAVING count(*) > 1
                    ) g ON r.ip = g.ip;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW shared_ip_authors');
        
        Schema::table('ips', function (Blueprint $table) {
            $table->dropPrimary();
        });
        
        Schema::dropIfExists('ip');
    }
}
