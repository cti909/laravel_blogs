<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Like extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id', 
        'user_id', 
        'object_id', 
        'type_id'
    ];

    /**
     * TypeLIke
     */
    function typeLike() {
        return $this->belongsTo(TypeLike::class);
    }

    /**
     * User
     */
    function user() {
        return $this->belongsTo(User::class);
    }

    //-----------------------------------------function---------------------------------------------------
    /**
     * get: 
     * count like of posts
     * count of comments
     * current user is liked
     */
    static public function getPostsLike($user_id) {
        $type_post = 1;
        $type_comment = 2;
        
        $posts = Post::select(
            'posts.id as post_id',
            DB::raw('COUNT(DISTINCT likes.id) as post_likes_count'),
            DB::raw('COUNT(DISTINCT comments.id) as comments_count'),
            DB::raw('MAX(CASE WHEN likes.user_id = '.$user_id.' THEN 1 ELSE 0 END) as is_liked')
        )
        ->leftJoin('likes', function ($join) use ($type_post) {
            $join->on('likes.object_id', '=', 'posts.id')
                ->where('likes.type_id', '=', $type_post);
        })
        ->leftJoin('comments', 'comments.post_id', '=', 'posts.id')
        ->leftJoin('likes as my_likes', function ($join) use ($user_id, $type_post) {
            $join->on('my_likes.object_id', '=', 'posts.id')
                ->where('my_likes.user_id', '=', $user_id)
                ->where('my_likes.type_id', '=', $type_post);
        })
        ->groupBy('posts.id')
        ->get();
    
        return $posts;
    }
    

    /**
     * update like (post or comment) by user
     */
    static public function likeObjectAdd($user_id, $object_id, $type_id) {
        Like::create([
            'user_id' => $user_id,
            'object_id' => $object_id,
            'type_id' => $type_id,
        ]);
    }

    /**
     * delete like (post or comment) by user
     */
    static public function likeObjectDel($user_id, $object_id, $type_id) {
        Like::where('object_id', $object_id)
            ->where('user_id', $user_id)
            ->where('type_id', $type_id)
            ->delete();
    }
    
    /**
     * delete like (post or comment)
     */
    static public function deleteObjectLike($object_id, $type_id) {
        Like::where('object_id', $object_id)
            ->where('type_id', $type_id)
            ->delete();
    }
}
