<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Book;
use App\Author;
use App\Tag;
use Session;

class BookController extends Controller
{

    /**
    * GET
    * /books
    */
    public function index(Request $request) {
        $user = $request->user();
        # Note: We're getting the user from the request, but you can also get it like this:
        //$user = Auth::user();
        if($user) {
            # Approach 1)
            //$books = Book::where('user_id', '=', $user->id)->orderBy('title')->get();
            # Approach 2) Take advantage of Model relationships
            $books = $user->books()->orderBy('title')->get();
            # Get 3 most recently added books
            #$newBooks = Book::orderBy('created_at', 'descending')->limit(3)->get(); # Query DB
            $newBooks = $books->sortByDesc('created_at')->take(3); # Query existing Collection
        }
        else {
            $books = [];
            $newBooks = [];
        }
        return view('books.index')->with([
            'books' => $books,
            'newBooks' => $newBooks,
        ]);
    }

    /**
    * GET
    * /books/{id}
    */
    public function show($id) {
        $book = Book::find($id);
        if(!$book) {
            Session::flash('message', 'The book you requested could not be found.');
            return redirect('/');
        }
        return view('books.show')->with([
            'book' => $book,
        ]);
    }



        /**
    * GET
    * /search
    */
    public function search(Request $request) {

        # Start with an empty array of search results; books that
        # match our search query will get added to this array
        $searchResults = [];

        # Store the searchTerm in a variable for easy access
        # The second parameter (null) is what the variable
        # will be set to *if* searchTerm is not in the request.
        $searchTerm = $request->input('searchTerm', null);

        # Only try and search *if* there's a searchTerm
        if($searchTerm) {

            # Open the books.json data file
            # database_path() is a Laravel helper to get the path to the database folder
            # See https://laravel.com/docs/5.4/helpers for other path related helpers
            $booksRawData = file_get_contents(database_path().'/books.json');

            # Decode the book JSON data into an array
            # Nothing fancy here; just a built in PHP method
            $books = json_decode($booksRawData, true);

            # Loop through all the book data, looking for matches
            # This code was taken from v1 of foobooks we built earlier in the semester
            foreach($books as $title => $book) {

                # Case sensitive boolean check for a match
                if($request->has('caseSensitive')) {
                    $match = $title == $searchTerm;
                }
                # Case insensitive boolean check for a match
                else {
                    $match = strtolower($title) == strtolower($searchTerm);
                }

                # If it was a match, add it to our results
                if($match) {
                    $searchResults[$title] = $book;
                }

            }
        }

        

        # Return the view, with the searchTerm *and* searchResults (if any)
        return view('books.search')->with([
            'searchTerm' => $searchTerm,
            'caseSensitive' => $request->has('caseSensitive'),
            'searchResults' => $searchResults
        ]);
    }


    /**
    * GET
    * /books/new
    * Display the form to add a new book
    */
    public function createNewBook(Request $request) {

        # Get all the authors
        $authorsForDropdown = Author::getAuthorsFroDropdown();

        return view('books.new')->with([
            'authorsForDropdown' => $authorsForDropdown
        ]);
    }


    /**
    * POST
    * /books/new
    * Process the form for adding a new book
    */
    public function storeNewBook(Request $request) {
        # Custom error message
        $messages = [
            'author_id.not_in' => 'Author not selected.',
        ];
        $this->validate($request, [
            'title' => 'required|min:3',
            'published' => 'required|numeric',
            'cover' => 'required|url',
            'purchase_link' => 'required|url',
            'author_id' => 'not_in:0',
        ], $messages);

        # Add new book to database
        $book = new Book();
        $book->title = $request->title;
        $book->published = $request->published;
        $book->cover = $request->cover;
        $book->purchase_link = $request->purchase_link;
        $book->author_id = $request->author_id;
        $book->user_id = $request->user()->id; # <--- NEW LINE
        $book->save();

        # Now handle tags.
        # Note how the book has to be created (save) first *before* tags can
        # be added; this is because the tags need a book_id to associate with
        # and we don't have a book_id until the book is created.
        $tags = ($request->tags) ?: [];
        $book->tags()->sync($tags);
        $book->save();

        Session::flash('message', 'The book '.$request->title.' was added.');

        # Redirect the user to book index
        return redirect('/books');
    }


    /**
     * GET
     * /books/edit/{{id}}
     * show form to edit a book
     */
    public function edit($id) {

        $book = Book::with('tags')->find($id);

        if(is_null($book)) {
            Session::flash('message', 'Book not found.');
            return redirect('/books');
        } 

        # Get all the authors
        $authorsForDropdown = Author::getAuthorsFroDropdown();

        $tagsForCheckboxes = Tag::getTagsForCheckBoxes();

         # Create a simple array of just the tag names for tags associated with this book;
        # will be used in the view to decide which tags should be checked off
        $tagsForThisBook = [];

        foreach($book->tags as $tag) {
            $tagsForThisBook[] = $tag->name;
        }

        # Results in an array like this: $tagsForThisBook => ['novel','fiction','classic'];

        
        return view('books.edit')->with([
            'id' => $id,
            'book' => $book,
            'authorsForDropdown' => $authorsForDropdown,
            'tagsForCheckboxes' => $tagsForCheckboxes,
            'tagsForThisBook' => $tagsForThisBook
        ]);

    }

    /**
     * POST
     * /books/edit
     * process form to save edits to a book
     */
    public function saveEdits(Request $request) {

        $this->validate($request, [
            'title' => 'required|min:3',
            'published' => 'required|numeric',
            'cover' => 'required|url',
            'purchase_link' => 'required|url'
        ]);

        $book = Book::find($request->id);

        # Edit a new book to the database
        $book->title = $request->title;
        $book->published = $request->published;
        $book->cover = $request->cover;
        $book->purchase_link = $request->purchase_link;
        $book->author_id = $request->author_id;


        # If there were tags selected...
            if($request->tags) {
                $tags = $request->tags;
            }
            # If there were no tags selected (i.e. no tags in the request)
            # default to an empty array of tags
            else {
                $tags = [];
            }

            # Above if/else could be condensed down to this: $tags = ($request->tags) ?: [];

            # Sync tags
            $book->tags()->sync($tags);
            $book->save();


        Session::flash('message', 'Your changes were saved.');
        return redirect('/books/edit/'.$request->id);


    }

    /**
    * GET
    * Page to confirm deletion
    */
    public function confirmDeletion($id) {
        # Get the book they're attempting to delete
        $book = Book::find($id);
        if(!$book) {
            Session::flash('message', 'Book not found.');
            return redirect('/books');
        }
        return view('books.delete')->with('book', $book);
    }
    /**
    * POST
    * Actually delete the book
    */
    public function delete(Request $request) {

        # Get the book to be deleted
        $book = Book::find($request->id);

        if(!$book) {
            Session::flash('message', 'Deletion failed; book not found.');
            return redirect('/books');
        }

        $book->tags()->detach();
        
        $book->delete();
        
        # Finish
        Session::flash('message', $book->title.' was deleted.');
        return redirect('/books');
    }


} /* end extends*/  