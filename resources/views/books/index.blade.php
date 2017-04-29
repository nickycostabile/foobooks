@extends('layouts.master')

@push('head')
    <link href='/css/books.css' rel='stylesheet'>
@endpush

@section('title')
    Book Index
@endsection

@section('content')

    @if(count($newBooks) > 0)
        <section id='newBooks'>
            <h2>Latest additions to the Foobooks library</h2>
            <ul>
            @foreach($newBooks as $book)
                {{-- Note: diffForHumans is a built in method available to Carbon timestamps, read more here: http://carbon.nesbot.com/docs/ --}}
                <li class='truncate'><a href='/books/{{ $book->id }}'>{{ $book->title }}</a> added {{ $book->created_at->diffForHumans()}}</li>
            @endforeach
            </ul>
        </section>
    @endif

    <section id='books' class='cf'>
        <h2>Your Books</h2>
        @if(count($books) == 0)
            You don't have any books yet; would you like to <a href='/books/new'>add one</a>?
        @else
            @foreach($books as $book)

                <div class='book cf'>

                    <a href='/books/{{ $book->id }}'><img class='cover' src='{{ $book->cover }}' alt='Cover for {{ $book->title }}'></a>

                    <a href='/books/{{ $book->id }}'><h3>{{ $book->title }}</h3></a>

                    <a class='bookAction' href='/books/edit/{{ $book->id }}'><i class='fa fa-pencil'></i></a>
                    <a class='bookAction' href='/books/{{ $book->id }}'><i class='fa fa-eye'></i></a>
                    <a class='bookAction' href='/books/delete/{{ $book->id }}'><i class='fa fa-trash'></i></a>

                </div>
            @endforeach
        @endif
    </section>

@endsection