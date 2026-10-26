<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        // validasi: hanya application/json atau application/xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $posts = Post::orderBy("id", "DESC")->paginate(10);

            $outPut = [
                "message" => "posts",
                "results" => $posts
            ];

            // Jika request minta JSON
            if ($acceptHeader === 'application/json') {
                return response()->json($posts, 200);
            } 
            
            // Jika request minta XML (Tantangan / Set Response Format)
            else {
                $xml = new \SimpleXMLElement('<posts/>');
                foreach ($posts->items('data') as $item) {
                    $xmlItem = $xml->addChild('post');
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('title', $item->title);
                    $xmlItem->addChild('status', $item->status);
                    $xmlItem->addChild('content', $item->content);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }

        } else {
            return response('Not Acceptable!', 406);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        // validasi Accept Header: hanya application/json atau application/xml
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            // validasi Content-Type Header: hanya application/json
            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();
                $post = Post::create($input);

                // Response JSON
                if ($acceptHeader === 'application/json') {
                    return response()->json($post, 200);
                } 
                
                // Response XML untuk Create (Tantangan 1)
                else {
                    $xml = new \SimpleXMLElement('<post/>');
                    $xml->addChild('id', $post->id);
                    $xml->addChild('title', $post->title);
                    $xml->addChild('status', $post->status);
                    $xml->addChild('content', $post->content);
                    $xml->addChild('user_id', $post->user_id);
                    $xml->addChild('created_at', $post->created_at);
                    $xml->addChild('updated_at', $post->updated_at);

                    return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
                }

            } else {
                return response('Unsupported Media Type', 415);
            }

        } else {
            return response('Not Acceptable!', 406);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        // Validasi Accept Header untuk function show (Tantangan 2)
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $post = Post::find($id);

            if (!$post) {
                return response('Not Found', 404);
            }

            if ($acceptHeader === 'application/json') {
                return response()->json($post, 200);
            } else {
                $xml = new \SimpleXMLElement('<post/>');
                $xml->addChild('id', $post->id);
                $xml->addChild('title', $post->title);
                $xml->addChild('status', $post->status);
                $xml->addChild('content', $post->content);
                $xml->addChild('user_id', $post->user_id);
                $xml->addChild('created_at', $post->created_at);
                $xml->addChild('updated_at', $post->updated_at);

                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
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
        $acceptHeader = $request->header('Accept');

        // Validasi Accept Header untuk update (Tantangan 2)
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            // Validasi Content-Type Header untuk update (Tantangan 3)
            if ($contentTypeHeader === 'application/json') {
                $post = Post::find($id);

                if (!$post) {
                    return response('Not Found', 404);
                }

                $input = $request->all();
                $post->fill($input);
                $post->save();

                if ($acceptHeader === 'application/json') {
                    return response()->json($post, 200);
                } else {
                    $xml = new \SimpleXMLElement('<post/>');
                    $xml->addChild('id', $post->id);
                    $xml->addChild('title', $post->title);
                    $xml->addChild('status', $post->status);
                    $xml->addChild('content', $post->content);
                    $xml->addChild('user_id', $post->user_id);
                    $xml->addChild('created_at', $post->created_at);
                    $xml->addChild('updated_at', $post->updated_at);

                    return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
                }

            } else {
                return response('Unsupported Media Type', 415);
            }

        } else {
            return response('Not Acceptable!', 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        // Validasi Accept Header untuk destroy/delete (Tantangan 2)
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $post = Post::find($id);

            if (!$post) {
                return response('Not Found', 404);
            }

            $post->delete();
            $message = ['message' => 'deleted successfully', 'post_id' => $id];

            if ($acceptHeader === 'application/json') {
                return response()->json($message, 200);
            } else {
                $xml = new \SimpleXMLElement('<response/>');
                $xml->addChild('message', 'deleted successfully');
                $xml->addChild('post_id', $id);

                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }
}