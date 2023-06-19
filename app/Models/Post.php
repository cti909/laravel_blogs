<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'content', 
        'photo', 
        'creator_id',
        'category_id',
        'created_at',
        'updated_at'
    ];

    /**
     * User
     */
    function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Catgegory
     */
    function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * Comments
     */
    function comments() {
        return $this->hasMany(Comment::class);
    }

    //-----------------------------------------function---------------------------------------------------
    /**
     * get post count by category, search_token
     */
    static public function getTotalCount($category, $search_token) {
        $query = Post::query()->select(DB::raw('COUNT(*)'));
        if ($category == 0) {
            $query->where('content', 'LIKE', '%' . $search_token . '%');
        } else {
            $query->where('content', 'LIKE', '%' . $search_token . '%')
                ->where('category_id', $category);
        }
        $count = $query->count();
        return $count;
    }

    /**
     * get information of post by category, search_token, sort, page
     */
    static public function getPosts($category, $sort, $search_token, $start, $page_limit) {
        $query = Post::query()
            ->select('posts.id', 'users.name', 'posts.content', 'posts.photo', 'posts.creator_id', 'posts.category_id','posts.created_at','posts.updated_at')
            ->join('users', 'users.id', '=', 'posts.creator_id')
            ->where('posts.content', 'LIKE', '%' . $search_token . '%');   
        if ($category != 0) {
            $query->where('posts.category_id', $category);
        }
        $query->orderBy('posts.updated_at', $sort)
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($page_limit);
        $posts = $query->get();
        return $posts;
    }

    /**
     * get detail of post by post_id
     */
    static public function getDetailPost($post_id) {
        $post = Post::query()
            ->select('posts.id', 'users.name', 'posts.content', 'posts.photo', 'posts.creator_id', 'posts.category_id','posts.created_at','posts.updated_at')
            ->join('users', 'users.id', '=', 'posts.creator_id')
            ->where('posts.id', $post_id)
            ->first();
        return $post;
    }

    /**
    * create post
    */
    static public function createPost($data) {
        Post::create([
            'content' => nl2br($data['content']), 
            'photo' => $data['photo'], 
            'posting_time' => $data['posting_time'], 
            'creator_id' => $data['creator_id'], 
            'category_id'=> $data['category_id'],

        ]);
    }
     /**
     * update post by post_id
     */
    static public function updatePost($post_id, $data) {
        $post = Post::findOrFail($post_id);
        $post->update($data);
    }
     /**
     * delete post by post_id
     */
    static public function deletePost($post_id) {
        Post::where('id', $post_id)->delete();
    }

}
