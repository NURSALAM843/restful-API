<?php

namespace App\Http\Controllers\API;

use App\Models\API\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;

class PostContoller extends Controller
{
    public function index(){
        $posts = Post::all();

        // return response()->json(['data' => $posts]);
        // fungsinya sama tapi menggunakan resource
        //kalau hasil lebih dari 1 pakai collection
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with('writer:id,email,username')->findOrFail($id);
        return New PostDetailResource($post);
    }
}
