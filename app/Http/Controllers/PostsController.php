<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorization
        if (Gate::denies('read-post')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized',
            ], 403);
        }

        if (Auth::user()->role === 'admin') {
            $posts = Post::orderBy('id', 'DESC')->paginate(2)->toArray();
        } else {
            $posts = Post::where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->paginate(2)
                ->toArray();
        }

        $response = [
            'total_count' => $posts['total'],
            'limit' => $posts['per_page'],
            'pagination' => [
                'next_page' => $posts['next_page_url'],
                'current_page' => $posts['current_page'],
            ],
            'data' => $posts['data'],
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('create-post')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized',
            ], 403);
        }

        $input = $request->all();

        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
        ];

        $validator = Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $input['user_id'] = Auth::user()->id;

        $post = Post::create($input);

        return response()->json($post, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            abort(404);
        }

        if (Gate::denies('show-post', $post)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized',
            ], 403);
        }

        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $post = Post::find($id);

        if (!$post) {
            abort(404);
        }

        // authorization
        if (Gate::denies('update-post', $post)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized',
            ], 403);
        }
        // authorization end

        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id',
        ];

        $validator = Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post->fill($input);
        $post->save();

        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            abort(404);
        }

        if (Gate::denies('delete-post', $post)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized',
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'deleted successfully',
            'post_id' => $id,
        ], 200);
    }

    /**
     * Display a public listing of posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicIndex()
    {
        $posts = Post::orderBy('id', 'DESC')->get();

        return response()->json($posts, 200);
    }

    /**
     * Display a specified public post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function publicShow($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json($post, 200);
    }
}