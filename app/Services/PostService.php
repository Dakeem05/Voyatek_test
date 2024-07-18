<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Post;

class PostService
{
    public function index (Int $id)
    {
        $post = Post::where('blog_id', $id)->latest()->paginate(15);
        return $post;
    }

    public function store ($request, $user_id) {

        $contains_images = false;
        $images = [];

        if (isset($request->images)) {
            foreach ($request->images as $key => $image) {
                $imagee = Str::random(25).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path().'/uploads/images/postImages/';
                $image->move($destinationPath, $imagee);
                $path = config('app.url').'/images/'.$imagee;
                $images[] = $path;
                $contains_images = true;
            }
        }
        $post = Post::create([
            'title' => $request->title,
            'content' => isset($request->content) ? $request->content : null,
            'user_id' => $user_id,
            'blog_id' => $request->blog_id,
            'images' =>  $contains_images ? $images : null,
        ]);
        return $post;   
    }

    public function show (Int $id)
    {
        $post = Post::where('id', $id)->with('likes')->with('comments')->first();
        return $post;
    }

    public function update (Object $request, Int $id)
    {
        $post = Post::where('id', $id)->first();
        if ($post !== null) {
            $contains_images = false;
            $images = [];

            if (isset($request->images)) {
                foreach ($request->images as $key => $image) {
                    $imagee = Str::random(25).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path().'/uploads/images/postImages/';
                    $image->move($destinationPath, $imagee);
                    $path = config('app.url').'/images/'.$imagee;
                    $images[] = $path;
                    $contains_images = true;
                }
            }

            $post->update([
                'title' => isset($request->title)? $request->title : $post->title,
                'content' => isset($request->content) ? $request->content : $post->content,
                'images' =>  $contains_images ? $images : $post->images,
            ]);
            return true;
        }
        return false; 
    }

    public function delete (Int $id)
    {
        $post = Post::find($id);
        if ($post !== null) {
            $post->forceDelete();
            return true;
        }
        return true;
    }
}