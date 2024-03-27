<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\DeleteCommentsRequest;
use App\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCommentRequest $request)
    {
        //
        $this->commentRepository->createComment([
            'body' => $request->body,
            'post_id' => $request->post_id
        ]);

        return response()->json(
            ['message' => 'Comment created successfully']
        );
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCommentsRequest $request)
    {
        $this->commentRepository->deleteComments($request->post_id, $request->comments);
        return response()->json(
            ['message' => 'Comments deleted successfully']
        );
    }
}
