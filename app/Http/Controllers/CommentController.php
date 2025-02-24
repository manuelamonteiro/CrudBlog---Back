<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param \Illuminate\Http\Request $request  // The HTTP request instance
     * @param int $post_id  // ID of the post to comment on
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::findOrFail($post_id);

        $comment = Comment::create([
            'user_id' => auth()->id(), 
            'post_id' => $post->id,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment
        ], 201);
    }

    /**
     * Update the specified comment in storage.
     *
     * @param \Illuminate\Http\Request $request  // The HTTP request instance
     * @param int $id  // Comment ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $post_id, $id)
    {
        $comment = Comment::where('post_id', $post_id)->findOrFail($id);

        $this->authorize('update', $comment);
    
        $validator = Validator::make($request->all(), [
            'content' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $comment->update($request->only('content'));
    
        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment
        ]);
    }    

    /**
     * Remove the specified comment from storage.
     *
     * @param int $id  // Comment ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($post_id, $id)
    {
        $comment = Comment::where('post_id', $post_id)->findOrFail($id);

        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
