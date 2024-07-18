<?php

namespace App\Services;

use App\Models\Like;

class LikeService
{
    public function store ($request, $user_id) {
        $like = Like::where('user_id', $user_id)->where('post_id', $request->post_id)->first();

        if ($like == null) {
            Like::create([
                'user_id' => $user_id,
                'post_id' => $request->post_id,
            ]);
            return true; 
        } 
        $like->forceDelete();
        return false;
    }
}