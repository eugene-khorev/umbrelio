<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id')->unsigned();
            $table->ipAddress('ip');
            $table->string('title');
            $table->text('content');
            $table->integer('rating_total')->unsigned();
            $table->integer('rating_count')->unsigned();
            $table->float('rating');
            
            $table->index('rating_count');
            $table->index('rating');
            $table->index('author_id');
            $table->foreign('author_id')->references('id')->on('authors');
        });
        
        \DB::statement("
            CREATE OR REPLACE FUNCTION post_rating() RETURNS trigger AS '
                BEGIN
                        IF NEW.rating_count > 0 THEN
                            NEW.rating := CAST (NEW.rating_total AS FLOAT) / NEW.rating_count;
                        END IF;
                        RETURN NEW;
                END;
                ' LANGUAGE plpgsql
            ");
        
        \DB::statement("CREATE TRIGGER post_rating BEFORE INSERT OR UPDATE ON posts FOR EACH ROW EXECUTE PROCEDURE post_rating()");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP TRIGGER IF EXISTS post_rating ON posts");
        
        \DB::statement("DROP FUNCTION IF EXISTS post_rating()");
        
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
        });
        
        Schema::dropIfExists('post');
    }
}
