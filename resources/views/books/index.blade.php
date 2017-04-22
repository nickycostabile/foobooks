@extends('layouts.master')

@section('title')
    Book Index
@endsection

@section('content')

	<div style="background-color: papayawhip; border: 1px solid black">
		
		<h2>New Books</h2>

		@foreach($newBooks as $book)
			<div class="book">
				<h3>{{ $book['title'] }}</h3>
			</div>
		@endforeach

	</div>

	<h1>All the books</h1>

	@foreach($books as $book)
		<div class="book">
			<h2>{{ $book['title'] }}</h2>
			<img src=' {{ $book['cover'] }} ' alt="Book cover for {{ $book['title'] }}">
			Published in: {{ $book['published'] }}
		</div>
	@endforeach

@endsection