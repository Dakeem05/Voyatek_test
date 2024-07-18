<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Services\LikeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use ApiResponseTrait;

    public function __construct (private LikeService $like_service)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LikeRequest $request)
    {
        $_data = (Object) $request->validated();

        $res = $this->like_service->store($_data, auth()->user()->id);
        if ($res) {
            return $this->successResponse(null, 'Post liked successfully.');
        }
        return $this->successResponse(null, 'Post unliked successfully.');
    }
}
