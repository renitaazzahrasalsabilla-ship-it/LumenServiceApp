<?php
namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index() {
        return response()->json(Tag::all());
    }
    public function store(Request $request) {
        $tag = Tag::create($request->all());
        return response()->json($tag, 201);
    }
    public function show($id) {
        return response()->json(Tag::find($id));
    }
    public function update(Request $request, $id) {
        $tag = Tag::find($id);
        $tag->update($request->all());
        return response()->json($tag);
    }
    public function destroy($id) {
        Tag::destroy($id);
        return response()->json(['message' => 'Tag deleted successfully']);
    }
}