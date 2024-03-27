<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CommentRepository implements CommentRepositoryInterface
{


    public function createComment(array $commentDetails)
    {

        return Auth::user()->comments()->create($commentDetails);
    }


    public function deleteComments($postId, array $commentIds)
    {
        foreach ($commentIds as $comment) {
            Comment::where("post_id", $postId)->where("id", $comment)->delete();
        }
    }
}
