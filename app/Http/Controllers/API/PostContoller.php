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
        $posts = Post::all(); // kalau tidak pakai loadnmissing, pakai with di eloquent 

        // return response()->json(['data' => $posts]);
        // fungsinya sama tapi menggunakan resource
        //kalau hasil lebih dari 1 pakai collection
        return PostDetailResource::collection($posts->loadMissing('writer:id,email,username'));
    }

    public function show($id)
    {
        $post = Post::with('writer:id,email,username')->findOrFail($id);
        return New PostDetailResource($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $request['author'] = auth()->user()->id;

        $post = Post:: create($request->all());
        return New PostDetailResource($post->loadMissing('writer:id,email,username'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return New PostDetailResource($post->loadMissing('writer:id,email,username'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        // return response()->json(["Message" => "Data Berhasil Dihapus"], 200);
        return response()->json($post);
    }

    
}
