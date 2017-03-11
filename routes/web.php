<?php

Route::get('/books', 'BookController@index');

Route::get('/books/{title?}','BookController@view');

Route::get('/', 'WelcomeController');

/* Practice */
Route::any('/practice/{n?}', 'PracticeController@index');

if(config('app.env') == 'local') {
	Route::get('/logs', function() { });
}