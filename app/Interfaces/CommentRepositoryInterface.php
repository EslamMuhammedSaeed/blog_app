<?php

namespace App\Interfaces;

interface CommentRepositoryInterface
{
    public function createComment(array $postDetails);
    public function deleteComments($postId, array $commentIds);
}
