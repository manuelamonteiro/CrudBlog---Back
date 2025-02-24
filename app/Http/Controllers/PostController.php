<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::with(['user', 'comments.user'])->get();
    
        return response()->json($posts);
    }

    /**
     * Display the specified post with its comments.
     *
     * @param int $id  // Post ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $post = Post::with('user', 'comments')->findOrFail($id);
        return response()->json($post);
    }

    /**
     * Store a newly created post in storage.
     *
     * @param \Illuminate\Http\Request $request  // The HTTP request instance
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post'    => $post
        ], 201);
    }

    /**
     * Update the specified post in storage.
     *
     * @param \Illuminate\Http\Request $request  // The HTTP request instance
     * @param int $id  // Post ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $validator = Validator::make($request->all(), [
            'title'   => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post->update($request->all());

        return response()->json([
            'message' => 'Post updated successfully',
            'post'    => $post
        ]);
    }

    /**
     * Remove the specified post from storage.
     *
     * @param int $id  // Post ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
