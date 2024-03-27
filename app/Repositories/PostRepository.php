<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class PostRepository implements PostRepositoryInterface
{
    public function getAllPosts()
    {
        return Post::with(['user', 'comments' => function ($q) {
            $q->with('user')->orderBy('created_at', 'desc');
        }])->orderBy('created_at', 'desc')->get();
    }

    public function createPost(array $postDetails)
    {
        return Post::create($postDetails);
    }

    public function savePostFile($file)
    {

        $saved_path = '';
        if ($file) {

            $path = 'public/logos/' . Carbon::now()->format('d-m-Y');
            $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
            $saved_path =  env('APP_URL') . '/storage/logos/' . Carbon::now()->format('d-m-Y') . '/' . $file_name;
            $file->storeAs($path, $file_name);
        }
        return $saved_path;
    }

    public function updatePost(Post $post, array $newDetails)
    {
        return $post->update($newDetails);
    }

    public function saveUpdatedPostFile(Post $post, $file)
    {
        $saved_path = $post->photo;
        if ($file) {
            if (File::exists(public_path($post->photo))) {
                File::delete(public_path($post->photo));
            }
            $path = 'public/logos/' . Carbon::now()->format('d-m-Y');
            $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
            $saved_path =  env('APP_URL') . '/storage/logos/' . Carbon::now()->format('d-m-Y') . '/' . $file_name;
            $file->storeAs($path, $file_name);
        }
        return $saved_path;
    }
    public function deletePost(Post $post)
    {
        if (File::exists(public_path($post->photo))) {
            File::delete(public_path($post->photo));
        }
        $post->delete();
    }
}
