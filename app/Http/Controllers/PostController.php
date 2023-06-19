<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Like;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * view show all posts in 1 page, categories,..
     */
    public function index()
    {
        //get parameters in url
		$category_id = 0; // all
        $sort = "desc"; // desc
        $search_token = "";
        $page_current = 1;
        if (request()->has("category")) {
            $category_id = request()->get("category");
        }
        if (request()->has("sort")) {
            $sort = request()->get("sort");
        }
        if (request()->has("search_token")) {
            $search_token = request()->get("search_token");
        }
        if (request()->has("page")) {
            $page_current = request()->get("page");
        }

        // excute, process
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = -1;
        }
		// dd($user_id);
        $likes = Like::getPostsLike($user_id);
		// pagination
		$page_limit = 5; // post/1page
		$start = ($page_current-1)*$page_limit;
        $post_total_count = Post::getTotalCount($category_id, $search_token); // count of total post
        $page_count = intval(ceil($post_total_count / $page_limit)); // count of page

        // get all post in page
        $posts = Post::getPosts($category_id, $sort, $search_token, $start, $page_limit);
        for($i=0; $i < count($posts); $i++) {
            // check user is created
            if (Auth::check() && Auth::user()->id==$posts[$i]["creator_id"]){
                $posts[$i]["is_creator"] = TRUE;
            } else {
                $posts[$i]["is_creator"] = FALSE;
            }
			$posts[$i]["post_likes_count"] = 0;
			$posts[$i]["comments_count"] = 0;
			$posts[$i]["is_liked"] = 0;
			for($j=0; $j <= count($likes); $j++) {
				if($posts[$i]["id"] == $likes[$j]["post_id"]) {
					$posts[$i]["post_likes_count"] = $likes[$j]["post_likes_count"];
					$posts[$i]["comments_count"] = $likes[$j]["comments_count"];
					$posts[$i]["is_liked"] = $likes[$j]["is_liked"];
					break;
				}
			}
        }
		// get categories
        $categories = Category::getAllCategories();
        $context = [
            'categories' => $categories,
            'category_id' => $category_id,
            'likes' => $likes,
            'posts' => $posts,
            'sort' => $sort,
            'search_token' => $search_token,
            'page_current' => $page_current,
            'page_count' => $page_count
        ];
        return view('posts.index', $context);
    }

	/**
	 * add and delete like post or comment
	 */
    public function likePost(Request $request, $post_id) {
		$action = $request->input('like');
		$type_post = 1;
		if($action == "del") {
			Like::likeObjectDel(Auth::user()->id, $post_id, $type_post);
		} else if($action == "add"){
			Like::likeObjectAdd(Auth::user()->id, $post_id, $type_post);
		}
		$response = [
			'status' => 'success'
		];
		return response()->json($response);
	}

	/**
	 * detail this post
	 */
	public function detail($post_id) //detail
	{ 
		if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = -1;
        }
		$likes = Like::getPostsLike($user_id);
		$post_detail = Post::getDetailPost($post_id);
		if (Auth::check() && Auth::user()->id==$post_detail["creator_id"]){
			$post_detail["is_creator"] = TRUE;
		} else {
			$post_detail["is_creator"] = FALSE;
		}
		for($i=0; $i <= count($likes); $i++) {
			if($post_detail["id"] == $likes[$i]["post_id"]) {
				$post_detail["post_likes_count"] = $likes[$i]["post_likes_count"];
				$post_detail["comments_count"] = $likes[$i]["comments_count"];
				$post_detail["is_liked"] = $likes[$i]["is_liked"];
				break;
			}
		}
		$context = [
            'likes' => $likes,
            'post_detail' => $post_detail,
        ];
        return view('posts.detail', $context);
		// $posts = post_model::getInstance();
		// $this->post_records = $posts->get_detail_post($params['id']);
		// // $this->setProperty('records',$this->records); //dang k=>v
		// $this->post = [];
		// $index = 0;
		// while($row = mysqli_fetch_array($this->post_records)){
		// 	if (isset($_SESSION['user_id']) && $_SESSION['user_id']==$row["creator_id"]) 
		// 		$this->post["is_creator"] = TRUE;
		// 	else $this->post["is_creator"] = FALSE;
		// 	$this->post["id"] = $row["id"];
		// 	$this->post["creator_name"] = $row["name"];
		// 	$this->post["content"] = $row["content"];
		// 	$this->post["photo"] = $row["photo"];
		// 	$this->post["creator_id"] = $row["creator_id"];
		// 	$this->post["posting_time"] = $row["posting_time"];
		// 	$this->post["creator_id"] = $row["creator_id"];
		// 	$this->post["post_likes_count"] = $params['post_likes_count'];
		// 	$this->post["comments_count"] = $params['comments_count'];
		// 	$this->post["is_liked"] = $params['is_liked'];
		// 	$index++;
		// }
		// $this->display();
	}

	/**
	 * create data in db and save image in public/media/posts
	 */
	public function create(Request $request)
	{
        // check 1 of 2 (content or image) is not null
        $request->validate([
            'category' => 'required',
            'content' => 'required_without_all:image',
            'image' => 'required_without_all:content|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
		$image_name = null;
        // param1 image, param2 folder in public/media to save image; return image name
		if($request->hasFile('image')) {
			$image_name = Controller::renameImage($request->file('image'), "posts");
			// get url image, resize this and save
			Controller::resizeImage($folder="posts", $image_name);
		}
		$data = [
			'content' => $request->input('content'),
			'photo' => $image_name,
			'posting_time' => Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
			'creator_id' => Auth::user()['id'],
			'category_id' => $request->input('category'),
		];
        Post::createPost($data);
		return redirect()->route('posts.index')->with('success','Your post was successful');
	}

	/**
	 * edit post
	 */
	public function update(Request $request, $post_id) {
		$content = $request->input('content');
		$image_name = null;
		if ($request->hasFile('image')) {
			$image_name = Controller::renameImage($request->file('image'), "posts");
			Controller::resizeImage($folder="posts", $image_name);
		}
		if(!empty($content) || $image_name != null) {
			$data = [
				'content' => $content,
				'photo' => $image_name,
			];
			Post::updatePost($post_id, $data);
			$response = [
				'content' => nl2br($content),
				'image' => "$image_name",
				'status' => 'success'
			];
		} else {
			$response = [
				'content' => nl2br($content),
				'image' => "$image_name",
				'status' => 'error'
			];
		}
		return response()->json($response);
	}
	
	/**
	 * delete post
	 */
	public function delete($post_id = 5)
	{
		Like::deleteObjectLike($post_id, $type_id=1); // delete post like
		$comments_by_post = Comment::getCommentsByPost($post_id);
		for($i=0; $i < count($comments_by_post); $i++) {
			Like::deleteObjectLike($comments_by_post[$i]['id'], $type_id=2); // delete comments
		}
		Comment::deleteCommentsByPost($post_id); // delete comments
		Post::deletePost($post_id); // delete post
		$response = [
			'status' => 'sucess'
		];
		return response()->json($response);
	}
}
