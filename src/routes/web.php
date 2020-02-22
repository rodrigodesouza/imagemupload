<?php

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

Route::get('/upload-imagem-test', 'ImagemUploadController@testUploadImagem');

Route::post('/pot-upload-imagem-test', 'ImagemUploadController@uploadImagem');

Route::get('img-render/{path}/{tamanho?}/{imagem?}', [
	'as' => 'imagem.render',
	'uses' => 'ImagemUploadController@imagemRender',
]);

Route::get('img-delete/{imagem}', [
	'as' => 'imagem.delete',
	'uses' => 'ImagemUploadController@imagemDelete',
]);
use Intervention\Image\ImageManagerStatic as Image;

Route::get('img-load', function(){
    ini_set('memory_limit', -1);
    ini_set('max_execution_time', 0);
    ini_set('max_input_time', -1);
    ini_set('max_input_vars', -1);
    ini_set('post_max_size', -1);
    ini_set('upload_max_filesize', -1);
    ini_set('max_file_uploads', -1);
    set_time_limit(0);
    $path = storage_path('app/public/semgit/m/download_8f0d0ef99addd301c4e233f9232f45e7.jpeg');
    $url = Image::make($path);//->stream('jpg', 60);
    // dd($url);
    // $img = Image::cache(function($url) {
    //     // $image->make('public/foo.jpg')->resize(300, 200)->greyscale();
    //     $url->resize($_GET['h'], $_GET['w'])->response('jpg', 100);
    //  }, 10, true);

    //  return $img;

    return $url->fit($_GET['h'], $_GET['w'])->response('jpg', 100);

})->name('img-load');
