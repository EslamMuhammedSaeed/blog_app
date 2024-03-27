<?php

namespace App\Interfaces;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getAllPosts();
    public function createPost(array $postDetails);
    public function savePostFile($file);
    public function updatePost(Post $post, array $newDetails);
    public function saveUpdatedPostFile(Post $post, $file);
    public function deletePost(Post $post);
}
