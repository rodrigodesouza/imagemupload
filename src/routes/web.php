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
