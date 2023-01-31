<?php

namespace App\Http\Controllers\API;

use App\Models\API\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;

class PostContoller extends Controller
{
    public function index(){
        $posts = Post::all(); // kalau tidak pakai loadnmissing, pakai with di eloquent 

        // return response()->json(['data' => $posts]);
        // fungsinya sama tapi menggunakan resource
        //kalau hasil lebih dari 1 pakai collection
        return PostDetailResource::collection($posts->loadMissing(['writer:id,email,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function show($id)
    {
        $post = Post::with('writer:id,email,username')->findOrFail($id);
        return New PostDetailResource($post->loadMissing(['writer:id,email,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);
        $image = null;
        if($request->file){
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName.'.'.$extension;
            Storage::putFileAs('image', $request->file, $image);
        }

        $request['image'] = $image;
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

    private function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
