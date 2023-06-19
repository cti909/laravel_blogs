<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'content',
        'post_id', 
        'creator_id',
        'path', 
        'path_length',
        'created_at',
        'updated_at'
    ];

    /**
     * Post
     */
    function post() {
        return $this->belongsTo(Post::class);
    }

    /**
     * User
     */
    function user() {
        return $this->belongsTo(User::class);
    }

    //-----------------------------------------function---------------------------------------------------
    /**
     * check exist comment
     */
    static public function checkExistComment($post_id) {
        $commentCount = Comment::where('post_id', $post_id)->count();
        return $commentCount;
    }

    /**
     * get lastest comment by path
     */
    static public function getLatestCommentByPath($post_id, $path, $path_length) {
        $comment = Comment::where('post_id', $post_id)
            ->where('path_length', $path_length)
            ->where('path', 'LIKE', $path . '%')
            ->orderBy('id', 'desc')
            ->first();
        return $comment;
    }

    /**
     * check max id
     */
    static public function getMaxIdComment() {
        $maxId = Comment::max('id');
        return $maxId;
    }

    //-----------------main function------------------
    /**
     * get all comment by post_id and sort asc by path
     */
    static public function getAllComments($post_id) {
        $comments = DB::table('comments')
            ->join('users', 'users.id', '=', 'comments.creator_id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->select('users.name', 'comments.id', 'comments.content', 'comments.created_at', 'comments.post_id', 'comments.creator_id', 'comments.path')
            ->where('comments.post_id', $post_id)
            ->orderBy('comments.path', 'ASC')
            ->get();   
        return $comments;
    }

    /**
     * delete comment by post_id
     */
    static public function deleteCommentsByPost($post_id) {
        Comment::where('post_id', $post_id)->delete();
    }

    /**
     * get all comment_id by post_id
     */

    static public function getCommentsByPost($post_id) {
        $comments = Comment::where('post_id', $post_id)->get(['id']);
        return $comments;
    }

    /**
     * get comment with information, count of comment and check current user is liked
     */
    static public function getComments($user_id, $post_id, $type_id = 2) {
        $comments = DB::table('comments')
            ->select(
                'comments.id AS comment_id',
                'comments.content',
                'comments.path',
                'comments.path_length',
                'comments.created_at',
                'comments.creator_id',
                'users.name AS creator_name',
                DB::raw('COUNT(DISTINCT comment_likes.id) AS comment_likes_count'),
                DB::raw('MAX(IF(my_likes.user_id = ' . $user_id . ', 1, 0)) AS is_liked')
            )
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->leftJoin('likes AS comment_likes', function ($join) use ($type_id) {
                $join->on('comment_likes.object_id', '=', 'comments.id')
                    ->where('comment_likes.type_id', '=', $type_id);
            })
            ->leftJoin('likes AS my_likes', function ($join) use ($user_id, $type_id) {
                $join->on('my_likes.object_id', '=', 'comments.id')
                    ->where('my_likes.user_id', '=', $user_id)
                    ->where('my_likes.type_id', '=', $type_id);
            })
            ->join('users', 'comments.creator_id', '=', 'users.id')
            ->where('posts.id', $post_id)
            ->groupBy('comments.id')
            ->orderBy('comments.path', 'ASC')
            ->get();   
        return $comments;
    }

    /**
     * add
     */
    // static public function addComment($comment_id) {
    //     Comment::where('id', $comment_id)->delete();
    // }

    /**
     * delete comment by comment_id
     */
    static public function deleteComment($comment_id) {
        Comment::where('id', $comment_id)->delete();
    }

    /**
     * get information of comment by post_id and path%
     */

    static public function getCommentsByPath($post_id, $path) {
        $comments = Comment::where('post_id', $post_id)
            ->where('path', 'LIKE', $path . '%')
            ->get();
        return $comments;
    }
    
    /**
     * delete comment by post_id and path%
     */
    static public function deleteCommentByPath($post_id, $path) {
        Comment::where('post_id', $post_id)
            ->where('path', 'LIKE', $path . '%')
            ->delete();
    } 
}
