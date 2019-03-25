<?php
use Illuminate\View\Compilers\Concerns\CompilesComments;

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

// Route::get('/', function () {
//     return view('default');
// });

// Route::get('/gallery', 'ImagesController@index')->name('gallery');
Route::get('/', 'ImagesController@index')->name('gallery');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/gallery', function () {
//   return view('images-gallery');
// });

// Images
Route::get('/gallery', 'ImagesController@index')->name('gallery');
Route::post('/gallery', 'ImagesController@upload');
Route::delete('/images/{id}', 'ImagesController@destroy');
Route::put('/images/{id}/{title}', 'ImagesController@update');

Route::get('/imagepage/{id}', 'ImagesController@get');

// bonus img routes
Route::get('/mygallery', 'ImagesController@index')->name('mygallery');
Route::get('/getajaximages/{search}/{order}/{all}', 'ImagesController@get_by_username_or_title_sorted');

// Comments
Route::get('/comments/{id}', 'CommentController@get')->name('comments');
Route::post('/comments', 'CommentController@upload');

// users
Route::get('/getusers', 'UsersController@get')->name('getusers');
Route::get('/createuser', 'UsersController@create')->name('createuser');
Route::post('/add_user', 'UsersController@add_user')->name('add_user');
Route::get('/edituser/{id}', 'UsersController@update')->name('edituser');
Route::delete('/deleteuser/{id}', 'UsersController@delete')->name('deleteuser');

Route::post('/update_pressed', 'UsersController@update_pressed')->name('update_pressed');
