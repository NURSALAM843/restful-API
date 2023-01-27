<?php

namespace App\Http\Middleware;

use App\Models\API\Post;
use Closure;
use Illuminate\Http\Request;

class PemilikPostingan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUser = auth()->user();
        $post = Post::findOrFail($request->id);

        if($post->author != $currentUser->id){
            return response()->json(['message' => 'data not found'], 404);
        }
        return $next($request);
    }
}
