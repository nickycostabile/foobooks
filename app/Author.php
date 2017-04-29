<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
	/**
	 * Relationship Method
	 */
    public function books() {
		# Author has many Books
		# Define a one-to-many relationship.
		return $this->hasMany('App\Book');
	}

	/**
	 * 
	 */
	public static function getAuthorsFroDropdown() {

		# Get all the authors
        $authors = Author::orderBy('last_name', 'ASC')->get();

        # Organize the authors into an array where the key = author id and value = author name
        $authorsForDropdown = [];
        foreach($authors as $author) {
            $authorsForDropdown[$author->id] = $author->last_name.', '.$author->first_name;
        }

        return $authorsForDropdown;

	}
}
