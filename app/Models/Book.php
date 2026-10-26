<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Book -> table_name = books
    protected $table = 'books';

    // define column name
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'isbn',
        'year_published',
        'price',
        'stock',
        'status',
    ];

    // untuk melakukan update field created_at dan updated_at secara otomatis
    public $timestamps = true;
}
