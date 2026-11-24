<?php
namespace App\Http\Controllers\Public;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'DESC')->get();

        return response()->json($posts, 200);
    }

    public function show($postID)
    {
        $post = Post::find($postID);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json($post, 200);
    }

    public function create(Request $request)
    {
        $input = $request->all();

        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id'
        ];

        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = Post::create($input);

        return response()->json($post, 200);
    }
}
