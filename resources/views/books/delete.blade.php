@extends('layouts.master')

@section('title')
    Confirm deletion: {{ $book->title }}
@endsection

@section('content')

    <h1>Confirm deletion</h1>
    <form method='POST' action='/books/delete'>

        {{ csrf_field() }}

        <input type='hidden' name='id' value='{{ $book->id }}'?>

        <h2>Are you sure you want to delete <em>{{ $book->title }}</em>?</h2>

        <input type='submit' value='Yes, delete this book.' class='btn btn-danger'>

    </form>

@endsection