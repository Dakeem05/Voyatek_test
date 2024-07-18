<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreationRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\PostService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function __construct (private PostService $post_service)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(String $blog_id)
    {
        $res = $this->post_service->index($blog_id);
        return $this->successResponse($res);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreationRequest $request)
    {
        $_data = (Object) $request->validated();

        $res = $this->post_service->store($_data, auth()->user()->id);
        if ($res !== null) {
            return $this->successResponse($res, 'Post created successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = $this->post_service->show($id);
        return $this->successResponse($res);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->notFoundResponse('Post not found');
        }
        if (! Gate::allows('update-post', [$post, auth()->user()])) {
            return $this->unauthorizedResponse('Unauthorized Action');
        }
        $_data = (Object) $request->validated();

        $res = $this->post_service->update($_data, $id);
        if ($res) {
            return $this->successResponse($res, 'Post updated successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->notFoundResponse('Post not found');
        }
        if (! Gate::allows('update-post', [$post, auth()->user()])) {
            return $this->unauthorizedResponse('Unauthorized Action');
        }
        $res = $this->post_service->delete($id);
        if ($res) {
            return $this->successResponse($res, 'Post deleted successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }
}
