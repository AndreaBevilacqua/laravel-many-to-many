@extends('layouts.app')

@section('title', 'Crea Post')

@section('content')
<header>
    <h1 class="my-2">Nuovo Post</h1>
</header>

<hr>

@include('includes.posts.form')

@endsection

@section('scripts')
  @vite('resources/js/image_preview.js')
@endsection