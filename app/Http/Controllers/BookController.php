<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{

	/**
	* GET
	* /books
	*/
    public function index() {
    	return 'View all the books...';
    }

	/**
	* GET
	* /books/{$title?}
	*/
    public function view($title = null) {
    	return 'You want to view the book '.$title .'.';
    }
}
