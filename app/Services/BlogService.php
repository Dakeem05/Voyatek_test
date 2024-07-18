<?php

namespace App\Services;

use App\Models\Blog;

class BlogService
{
    public function index ()
    {
        $blog = Blog::paginate(15);
        return $blog;
    }

    public function store ($request, $user_id) {
        if (isset($request->image)) {
            $image = time().'.'.$request->image->getClientOriginalExtension();
            $destinationPath = public_path().'/uploads/images/blogImages/';
            $request->image->move($destinationPath, $image);
            $path = config('app.url').'/images/'.$image;
            $blog = Blog::create([
                'title' => $request->title,
                'description' => isset($request->desc) ? $request->desc : null,
                'user_id' => $user_id,
                'image' => $path
            ]);
        } else{
            $blog = Blog::create([
                'title' => $request->title,
                'description' => isset($request->desc) ? $request->desc : null,
                'user_id' => $user_id,
                'image' => null
            ]);
        }
        return $blog;   
    }

    public function show (Int $id)
    {
        $blog = Blog::where('id', $id)->with('posts')->first();
        return $blog;
    }

    public function update (Object $request, Int $id)
    {
        $blog = Blog::where('id', $id)->first();
        if ($blog !== null) {
            $contains_image = false;
            $path = '';

            if (isset($request->image)) {
                $image = time().'.'.$request->image->getClientOriginalExtension();
                $destinationPath = public_path().'/uploads/images/blogImages/';
                $request->image->move($destinationPath, $image);
                $path = config('app.url').'/images/'.$image;
                $contains_image = true;
            }

            $blog->update([
                'description' => isset($request->desc)? $request->desc : $blog->description,
                'title' => isset($request->title)? $request->title : $blog->title,
                'image' => $contains_image ? $path : $blog->image,
            ]);
            return true;
        }
        return false; 
    }

    public function delete (Int $id)
    {
        $blog = Blog::find($id);
        if ($blog !== null) {
            $blog->forceDelete();
            return true;
        }
        return true;
    }
}