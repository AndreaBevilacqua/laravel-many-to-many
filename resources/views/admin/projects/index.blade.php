@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<header>
    <h1>Projects</h1>
    <hr>
</header>

<table class="table table-dark table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Titolo</th>
        <th scope="col">Slug</th>
        <th scope="col">Tipo</th>
        <th scope="col">Stato</th>
        <th scope="col">Creato il</th>
        <th scope="col">Ultima modifica</th>
        <th>
          <div class="d-flex justify-content-end">
            <a href="{{ route('admin.projects.create')}}" class="btn btn-sm btn-success">
              <i class="fas fa-plus me-2"></i>Nuovo Proggetto
            </a>
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
        @forelse($projects as $project)

        <tr>
          <th scope="row">{{ $project->id}}</th>
          <td>{{ $project->title}}</td>
          <td>{{ $project->slug}}</td>
          <td>@if($project->type) <span class="badge" style="background-color: {{ $project->type->color }}"> {{ $project->type->label }} </span> @else Nessuna @endif</td>
          <td>{{ $project->is_published ? 'Pubblicato' : 'Bozza' }}</td>
          <td>{{ $project->getFormattedDate('created_at', 'd-m-Y H:i:s')}}</td>
          <td>{{ $project->getFormattedDate('updated_at', 'd-m-Y H:i:s')}}</td>
          <td>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.projects.show', $project)}}" class="btn btn-sm btn-primary">
                    <div class="i fas fa-eye"></div>
                </a>

                <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-pencil"></i></a>
       
               <form action="{{ route('admin.projects.destroy', $project)}}" method="POST" class="delete-form">
                   @csrf
                   @method('DELETE')
                   <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i></button>
               </form>
            </div>
          </td>
        </tr>

        @empty
        <tr>
            <td colspan="7">
                <h3 class="text-center">Non ci sono progetti</h3>
            </td>
        </tr>
        @endforelse

    </tbody>
  </table>

  @if($projects->hasPages())
    {{ $projects->links()}}
  @endif
@endsection

@section('scripts')
  @vite('resources/js/delete_confirmation.js')
@endsection