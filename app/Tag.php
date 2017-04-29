<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Relationship Method
     */
    public function books() {
    	$this->belongstoMany('App\Book')->withTimeStamps();
    }

    public static function getTagsForCheckboxes() {

	    $tags = Tag::orderBy('name','ASC')->get();

	    $tagsForCheckboxes = [];

	    foreach($tags as $tag) {
	        $tagsForCheckboxes[$tag['id']] = $tag->name;
	    }

	    return $tagsForCheckboxes;

	}
	
}
