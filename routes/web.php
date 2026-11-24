<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/hello-lumen', function () {
    return "<h1>Lumen</h1><p>Hi good developer, thank for using Lumen</p>";
});

$router->get('/hello-lumen/{name}', function ($name) {
    return "<h1>Lumen</h1><p>Hi <b>" . $name . "</b>, thank for using Lumen</p>";
});

$router->get('/scores', ['middleware' => 'login', function () {
    return "<h1>Selamat</h1><p>Nilai anda 100</p>";
}]);

$router->get('/user', 'UsersController@index');

$router->get('/service-status', function () {
    return response()->json([
        'service_name' => 'PHP Service App',
        'status' => 'Running'
    ]);
});

$router->get('/users', function () {
    return response()->json([
        [
            'id' => 1,
            'name' => 'Sumatrana',
            'email' => 'sumatrana@gmail.com',
            'address' => 'Padang',
            'gender' => 'Laki-laki'
        ],
        [
            'id' => 2,
            'name' => 'Jawarianto',
            'email' => 'jawarianto@gmail.com',
            'address' => 'Cimahi',
            'gender' => 'Laki-laki'
        ],
        [
            'id' => 3,
            'name' => 'Kalimantanio',
            'email' => 'kalimantanio@gmail.com',
            'address' => 'Samarinda',
            'gender' => 'Laki-laki'
        ],
        [
            'id' => 4,
            'name' => 'Sulawesiani',
            'email' => 'sulawesiani@gmail.com',
            'address' => 'Makasar',
            'gender' => 'Perempuan'
        ],
        [
            'id' => 5,
            'name' => 'Papuani',
            'email' => 'papuani@gmail.com',
            'address' => 'Jayapura',
            'gender' => 'Perempuan'
        ]
    ]);
});

$router->get('/users/{userId}', function ($userId) {
    $users = [
        1 => [
            'id' => 1,
            'name' => 'Sumatrana',
            'email' => 'sumatrana@gmail.com',
            'address' => 'Padang',
            'gender' => 'Laki-laki'
        ],
        2 => [
            'id' => 2,
            'name' => 'Jawarianto',
            'email' => 'jawarianto@gmail.com',
            'address' => 'Cimahi',
            'gender' => 'Laki-laki'
        ],
        3 => [
            'id' => 3,
            'name' => 'Kalimantanio',
            'email' => 'kalimantanio@gmail.com',
            'address' => 'Samarinda',
            'gender' => 'Laki-laki'
        ],
        4 => [
            'id' => 4,
            'name' => 'Sulawesiani',
            'email' => 'sulawesiani@gmail.com',
            'address' => 'Makasar',
            'gender' => 'Perempuan'
        ],
        5 => [
            'id' => 5,
            'name' => 'Papuani',
            'email' => 'papuani@gmail.com',
            'address' => 'Jayapura',
            'gender' => 'Perempuan'
        ]
    ];

    return response()->json($users[$userId] ?? ['message' => 'User not found']);
});

$router->get('categories', 'CategoriesController@index');
$router->post('categories', 'CategoriesController@store');
$router->get('categories/{id}', 'CategoriesController@show');
$router->put('categories/{id}', 'CategoriesController@update');
$router->delete('categories/{id}', 'CategoriesController@destroy');

// Route Authentication (Tanpa Middleware Auth)
$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');

// Route CRUD Posts yang diproteksi JWT Authentication (Menggunakan 'auth:api')
$router->group(['middleware' => ['auth:api']], function ($router) {
    $router->get('/posts', 'PostsController@index');
    $router->post('/posts', 'PostsController@store');
    $router->get('/posts/{id}', 'PostsController@show');
    $router->put('/posts/{id}', 'PostsController@update');
    $router->delete('/posts/{id}', 'PostsController@destroy');
});

$router->get('/tags', 'TagsController@index');
$router->post('/tags', 'TagsController@store');
$router->get('/tags/{id}', 'TagsController@show');
$router->put('/tags/{id}', 'TagsController@update');
$router->delete('/tags/{id}', 'TagsController@destroy');

$router->get('/books', 'BooksController@index');
$router->post('/books', 'BooksController@store');
$router->get('/books/{id}', 'BooksController@show');
$router->put('/books/{id}', 'BooksController@update');
$router->delete('/books/{id}', 'BooksController@destroy');

// Authentication Routes Group Tambahan
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->delete('logout', 'AuthController@logout');
     
    $router->get('/me', ['middleware' => 'auth:api', function () {
        return response()->json(auth()->user());
    }]);
});


// Public Endpoint
$router->get('/public/posts', 'Public\PostsController@index');
$router->get('/public/post/{postID}', 'Public\PostsController@show');
$router->post('/public/posts', 'Public\PostsController@create');

