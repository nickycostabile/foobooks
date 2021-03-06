{{-- /resources/views/books/new.blade.php --}}
@extends('layouts.master')

@section('title')
    Edit book: {{ $book->title }}
@endsection

@section('content')
    <h1>Edit a book: {{ $book->title }}</h1>

    <form method='POST' action='/books/edit'>
        {{ csrf_field() }}

        <small>* Required fields</small>
    <br>

        <input type='hidden' name='id' value='{{$book->id }}'>

        <label for='title'>* Title</label>
        <input type='text' name='title' id='title' value='{{ old('title', $book->title)}}'>
    

        <label for='published'>* Published Year</label>
        <input type='text' name='published' id='published' value='{{ old('published', $book->published) }}'>
    

        <label for='cover'>* URL to a cover image</label>
        <input type='text' name='cover' id='cover' value='{{ old('cover', $book->cover) }}'>
    

        <label for='purchase_link'>* Purchase Link</label>
        <input type='text' name='purchase_link' id='purchase_link' value='{{ old('purchase_link', $book->purchase_link) }}'>

        <label for='author_id'>* Author:</label>
        <select id='author_id' name='author_id'>
            @foreach($authorsForDropdown as $author_id => $authorName)
                 <option value='{{ $author_id }}' {{ ($book->author_id == $author_id) ? 'SELECTED' : '' }}>
                     {{ $authorName }}
                 </option>
             @endforeach
        </select>

        <label for='tagsForCheckboxes'>Tags:</label>
        @foreach($tagsForCheckboxes as $id => $name)
            <input
                type='checkbox'
                value='{{ $id }}'
                name='tags[]'
                {{ (in_array($name, $tagsForThisBook)) ? 'CHECKED' : '' }}
            >
            {{ $name }} <br>
        @endforeach

    <br>
    
        <input type='submit' value='Save Changes'>
    </form>


    @if(count($errors) > 0)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

@endsection