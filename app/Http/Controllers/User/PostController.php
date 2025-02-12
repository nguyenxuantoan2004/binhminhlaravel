<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    function index(){
        $posts = Post::where("status", operator: "public")->orderBy("id", "desc")->paginate(10);
        return view("frontend.post", compact("posts"));
    }

    function show(string $slug, int $id){
        $postId = $id;
        // dd($postId);
        $post = Post::find($postId);
        $relatedPosts = Post::where("status", operator: "public")->orderBy("id", "desc")->limit(5)->get();
        
        // dd($post);
        return view("frontend.detail-post", compact("post", "relatedPosts"));
    }
}
