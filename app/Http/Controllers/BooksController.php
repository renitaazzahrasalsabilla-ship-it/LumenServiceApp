<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * GET /books
     * Menampilkan daftar buku (terbaru dulu, 10 data per halaman).
     */
    public function index()
    {
        $books = Book::orderBy('id', 'DESC')->paginate(10);

        return response()->json($books, 200);
    }

    /**
     * POST /books
     * Membuat data buku baru.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'          => 'required|string|max:150',
            'author'         => 'required|string|max:100',
            'publisher'      => 'required|string|max:100',
            'isbn'           => 'required|string|max:20|unique:books,isbn',
            'year_published' => 'required|integer|min:1000|max:' . date('Y'),
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'status'         => 'required|in:available,unavailable',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    /**
     * GET /books/{id}
     * Menampilkan detail satu buku.
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book, 200);
    }

    /**
     * PUT /books/{id}
     * Mengubah data buku.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $this->validate($request, [
            'title'          => 'sometimes|required|string|max:150',
            'author'         => 'sometimes|required|string|max:100',
            'publisher'      => 'sometimes|required|string|max:100',
            'isbn'           => 'sometimes|required|string|max:20|unique:books,isbn,' . $id,
            'year_published' => 'sometimes|required|integer|min:1000|max:' . date('Y'),
            'price'          => 'sometimes|required|numeric|min:0',
            'stock'          => 'sometimes|required|integer|min:0',
            'status'         => 'sometimes|required|in:available,unavailable',
        ]);

        $book->fill($request->all());
        $book->save();

        return response()->json($book, 200);
    }

    /**
     * DELETE /books/{id}
     * Menghapus data buku.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'deleted successfully',
            'book_id' => $id,
        ], 200);
    }
}
