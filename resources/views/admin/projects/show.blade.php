@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<header>
    <h1 class="my-5">{{ $project->title }}</h1>
    <p>Tipo: @if($project->type) <span class="badge" style="background-color: {{ $project->type->color }}"> {{ $project->type->label }} </span> @else Nessuna @endif </p>
</header>

<hr>

<div class="clearfix">
    @if($project->image)
    <img src="{{ asset('storage/' . $project->image) }}" alt="{{$project->title}}" class="me-2 float-start">
    @endif
    <p>{{ $project->content }}</p>
    <div class="d-flex justify-content-between">
        <div>
            <strong>Creato il:</strong> {{ $project->getFormattedDate('created_at', 'd-m-Y H:i:s') }}
            <strong>Ultima modifica il:</strong> {{ $project->getFormattedDate('updated_at', 'd-m-Y H:i:s') }}
        </div>
        <div>
            @forelse($project->technologies as $technology)
            <span class="badge rounded-pill text-bg-{{ $technology->color}}">{{ $technology->label }}</span>
            @empty 
             Nulla
            @endforelse
        </div>
    </div>
</div>

<hr>

<footer class="d-flex justify-content-between align-times-center">
    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary"> 
        <i class="fas fa-arrow-left"></i>Torna indietro
    </a>

    <div class="d-flex justify-content-between gap-2">
        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning">
             <i class="fas fa-pencil me-2"></i> Modifica</a>

        <form action="{{ route('admin.projects.destroy', $project)}}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"> <i class="fas fa-trash me-2"></i>Elimina</button>
        </form>
    </div>
</footer>

@endsection

@section('scripts')
  @vite('resources/js/delete_confirmation.js')
@endsection