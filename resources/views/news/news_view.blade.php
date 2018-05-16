@extends('layouts.main')
@section('content')
<section class="generalContent">
    <header>
        <h1>{{ $news->title }}</h1>
    </header>
    <article>
        {!! fixImage($news->body,"content-images") !!}
    </article>
</section>
@endsection 