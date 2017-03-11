<?php

Route::get('/books', 'BookController@index');

Route::get('/books/{title?}','BookController@view');

Route::get('/', 'WelcomeController');

/* Practice Routes*/
Route::any('/practice/{n?}', 'PracticeController@index');

if(config('app.env') == 'local') {
	Route::get('/logs', function() { });
}

/* Laravel 5 Log Viewer Package */
if(config('app.env') == 'local') {
	Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
}