<?php

Route::get('/books', 'BookController@index');

# /views/books/new POST example
Route::get('/books/new', 'BookController@createNewBook');
Route::post('/books/new', 'BookController@storeNewBook');

Route::get('/books/{title?}','BookController@view');

Route::get('/', 'WelcomeController');

# /routes/web.php
Route::get('/search', 'BookController@search');

# Practice Routes
Route::any('/practice/{n?}', 'PracticeController@index');

if(config('app.env') == 'local') {
	Route::get('/logs', function() { });
}

# Laravel 5 Log Viewer Package
if(config('app.env') == 'local') {
	Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
}

