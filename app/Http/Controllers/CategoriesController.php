<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    // 1. Menampilkan semua data (dengan pagination)
    public function index()
    {
        $categories = Category::orderBy("id", "DESC")->paginate(10);
        return response()->json($categories, 200);
    }

    // 2. Menambah data baru (Insert)
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    // 3. Menampilkan detail data berdasarkan ID (Find)
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200);
    }

    // 4. Mengubah data berdasarkan ID (Update)
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->update($request->all());
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }

    // 5. Menghapus data berdasarkan ID (Delete)
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}