<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
//Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

//RUTAS DE PRUEBA

Route::get('/', function () {
    return '<h1>HOLA MUNDO con laravel</h1>';
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/pruebas/{nombre?}', function ($nombre = null) {

    $texto = '<h2>Texto desde una ruta</h2>';
    $texto .= 'Nombre: ' . $nombre;

    return view('pruebas', array(
'texto' => $texto
    ));
});

Route::get('animales', [PruebasController::class, 'index']);
Route::get('test-orm', [PruebasController::class, 'testOrm']);

//RUTAS DEL API
/* GET: para conseguir datos o recursos
 * POST: para guardar datos o recursos o hacer lógica desde un formulario
 * PUT: para actualizar recursos o datos
 * DELETE: para eliminar datos o recursos
 */

//Rutas de prueba
//Route::get('/usuario/pruebas', [UserController::class,'pruebas']);
//Route::get('/categoria/pruebas', [CategoryController::class,'pruebas']);
//Route::get('/entrada/pruebas', [PostController::class,'pruebas']);
//Rutas el Controlador de usuarios
Route::post('/api/register', [UserController::class, 'register']);
Route::post('/api/login', [UserController::class, 'login']);
Route::put('/api/user/update', [UserController::class, 'update']);
Route::post('/api/user/upload', [UserController::class, 'upload'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::Class);
Route::get('/api/user/avatar/{filename}', [UserController::class, 'getImage']);
Route::get('/api/user/detail/{id}', [UserController::class, 'detail']);

//Rutas del controlador de categorías
Route::resource('api/category', CategoryController::class);

//Rutas del controlador de entradas/posts
Route::resource('api/posts', PostController::class);
Route::post('/api/posts/upload', [PostController::class, 'upload']);
Route::get('/api/posts/image/{filename}', [PostController::class, 'getImage']);
Route::get('/api/posts/category/{id}', [PostController::class, 'getPostsByCategory']);
Route::get('/api/posts/user/{id}', [PostController::class, 'getPostsByUser']);