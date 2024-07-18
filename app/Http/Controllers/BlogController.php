<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCreationRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Models\Blog;
use App\Services\BlogService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private BlogService $blog_service)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $res = $this->blog_service->index();
        return $this->successResponse($res);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCreationRequest $request)
    {
        $_data = (Object) $request->validated();

        $res = $this->blog_service->store($_data, auth()->user()->id);
        if ($res !== null) {
            return $this->successResponse($res, 'Blog created successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = $this->blog_service->show($id);
        return $this->successResponse($res);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogUpdateRequest $request, string $id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return $this->notFoundResponse('Blog not found');
        }
        if (! Gate::allows('update-blog', [$blog, auth()->user()])) {
            return $this->unauthorizedResponse('Unauthorized Action');
        }
        $_data = (Object) $request->validated();

        $res = $this->blog_service->update($_data, $id);
        if ($res) {
            return $this->successResponse($res, 'Blog updated successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return $this->notFoundResponse('Blog not found');
        }
        if (! Gate::allows('update-blog', [$blog, auth()->user()])) {
            return $this->unauthorizedResponse('Unauthorized Action');
        }
        $res = $this->blog_service->delete($id);
        if ($res) {
            return $this->successResponse($res, 'Blog deleted successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }
}
