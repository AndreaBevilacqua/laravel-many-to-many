@extends('layouts.app')

@section('title', 'Modifica Post')

@section('content')
<header>
    <h1 class="my-2">Modifica Post</h1>
</header>

<hr>

@include('includes.posts.form')

@endsection

@section('scripts')
    @vite('resources/js/image_preview.js')
@endsection