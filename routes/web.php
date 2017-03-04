<?php

Route::get('/books', 'BookController@index');

Route::get('/books/{title?}','BookController@view');

Route::get('/', 'WelcomeController');
