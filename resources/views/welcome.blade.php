@extends('layouts.master')

@push('head')
    <link href='/css/books.css' rel='stylesheet'>
@endpush

@section('title')
    Foobooks
@endsection

@section('content')

	<h1>Welcome!</h1>
    Welcome to Foobooks, a personal book organizer.
    To get started <a href='/login'>login</a> or <a href='/register'>register</a>.

@endsection