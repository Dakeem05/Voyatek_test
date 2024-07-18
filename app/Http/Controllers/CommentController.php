<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentCreationRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Services\CommentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    use ApiResponseTrait;

    public function __construct (private CommentService $comment_service)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentCreationRequest $request)
    {
        $_data = (Object) $request->validated();

        $res = $this->comment_service->store($_data, auth()->user()->id);
        if ($res !== null) {
            return $this->successResponse($res, 'Comment created successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = $this->comment_service->show($id);
        return $this->successResponse($res);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, string $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return $this->notFoundResponse('Comment not found');
        }
        if (! Gate::allows('update-comment', [$comment, auth()->user()])) {
            return $this->unauthorizedResponse('Unauthorized Action');
        }
        $_data = (Object) $request->validated();

        $res = $this->comment_service->update($_data, $id);
        if ($res) {
            return $this->successResponse($res, 'Comment updated successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return $this->notFoundResponse('Comment not found');
        }
        if (! Gate::allows('update-comment', [$comment, auth()->user()])) {
            return $this->unauthorizedResponse('Unauthorized Action');
        }
        $res = $this->comment_service->delete($id);
        if ($res) {
            return $this->successResponse($res, 'Comment deleted successfully.');
        }
        return $this->serverErrorResponse('An error occurred.');
    }
}
