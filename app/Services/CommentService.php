<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Str;

class CommentService
{
    public function store ($request, $user_id) {

        $contains_images = false;
        $images = [];

        if (isset($request->images)) {
            foreach ($request->images as $key => $image) {
                $imagee = Str::random(25).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path().'/uploads/images/commentImages/';
                $image->move($destinationPath, $imagee);
                $path = config('app.url').'/images/'.$imagee;
                $images[] = $path;
                $contains_images = true;
            }
        }
        $comment = Comment::create([
            'content' => isset($request->content) ? $request->content : null,
            'user_id' => $user_id,
            'post_id' => $request->post_id,
            'images' =>  $contains_images ? $images : null,
        ]);
        return $comment;   
    }

    public function show (Int $id)
    {
        $comment = Comment::find($id);
        return $comment;
    }

    public function update (Object $request, Int $id)
    {
        $comment = Comment::where('id', $id)->first();
        if ($comment !== null) {
            $contains_images = false;
            $images = [];

            if (isset($request->images)) {
                foreach ($request->images as $key => $image) {
                    $imagee = Str::random(25).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path().'/uploads/images/commentImages/';
                    $image->move($destinationPath, $imagee);
                    $path = config('app.url').'/images/'.$imagee;
                    $images[] = $path;
                    $contains_images = true;
                }
            }

            $comment->update([
                'content' => isset($request->content) ? $request->content : $comment->content,
                'images' =>  $contains_images ? $images : $comment->images,
            ]);
            return true;
        }
        return false; 
    }

    public function delete (Int $id)
    {
        $comment = Comment::find($id);
        if ($comment !== null) {
            $comment->forceDelete();
            return true;
        }
        return true;
    }
}