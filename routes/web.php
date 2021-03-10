<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions', 'QuestionsController')->except('show');
//Route::post('/questions/{question}/answers', 'AnswersController@store')->name('answers.store');
Route::resource('questions.answers', 'AnswersController')->only(['store', 'edit', 'update', 'destroy']);
Route::get('questions/{slug}', 'QuestionsController@show')->name('questions.show');
Route::post('/answers/{answer}/accept', 'AcceptAnswerController@index')->name('answers.accept');
Route::post('/questions/{question}/favourites', 'FavouritesController@store')->name('questions.favourite');
Route::delete('/questions/{question}/favourites', 'FavouritesController@destroy')->name('questions.unfavourite');
Route::post('/questions/{question}/vote', 'VoteQuestionController@index');
Route::post('/answers/{answer}/vote', 'VoteAnswerController@index');
