<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return $this->postRepository->getAllPosts();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {


        $saved_path = $this->postRepository->savePostFile($request->file('photo'));
        $this->postRepository->createPost([

            'title' => $request->title,
            'body' => $request->body,
            'photo' => $saved_path,
            'user_id' => $request->user()->id

        ]);
        return redirect("home")->with("status", "Post created successfully");
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        $saved_path = $this->postRepository->saveUpdatedPostFile($post, $request->file('photo'));
        $this->postRepository->updatePost($post, [
            'title' => $request->title,
            'body' => $request->body,
            'photo' => $saved_path,
        ]);

        return response()->json(
            ['message' => 'Post updated successfully']
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        $this->postRepository->deletePost($post);

        return response()->json(
            ['message' => 'Post deleted successfully']
        );
    }
}
