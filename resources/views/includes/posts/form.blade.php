@if($project->exists)
<form action="{{ route('admin.projects.update', $project)}}" method="POST" enctype="multipart/form-data" novalidate>
    @method('PUT')
    
    @else
    <form action="{{ route('admin.projects.store')}}" method="POST" enctype="multipart/form-data" novalidate>
    
@endif

    @csrf
    
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="title" class="form-label">Titolo</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @elseif(old('title', '')) is-valid @enderror" id="title" placeholder="Titolo..." value="{{old('title', $project->title)}}" required>
                @error('title')
                <div class="invalid-feedback">
                    {{ $message}}
                </div>
                @enderror
              </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="content" class="form-label">Contenuto del post</label>
                <textarea name="content" class="form-control @error('content') is-invalid @elseif(old('content', '')) is-valid @enderror" id="content" rows="10" required>
                    {{old('content', $project->content)}}
                </textarea>
                @error('content')
                <div class="invalid-feedback">
                    {{ $message}}
                </div>
                @enderror
              </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="type_id" class="form-label">Seleziona Categoria </label>
                <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @elseif(old('type_id', '')) is-valid @enderror">
                    <option value="">Nessuna</option>
                    @foreach ( $types as $type )
                        <option value="{{ $type->id }}" @if(old('type_id', $project->type?->id) == $type->id) selected  @endif> {{ $type->label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-5">
            <div class="mb-3">
                <label for="image" class="form-label">Inserisci un'immagine</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @elseif(old('image', '')) is-valid @enderror" id="image" placeholder="Immagine..." value="{{old('image', $project->image)}}">
                @error('image')
                <div class="invalid-feedback">
                    {{ $message}}
                </div>
                @else
                <div class="form-text">
                    Carica un file immagine
                </div>
                @enderror
            </div>
        </div>
        <div class="col-1">
            <div class="mb-3">
            <img src="{{ old('image', $project->image) 
                ? asset('storage/' . old('image', $project->image))  
                : 'https://marcolanci.it/boolean/assets/placeholder.png' }}" 
                class="img-fluid" alt="immagine post" id="preview">
            </div>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" @if(old('is_published', $project->is_published)) checked @endif>
                <label class="form-check-label" for="is_published">
                  Pubblicato
                </label>
              </div>
              
        </div>
    </div>
    <hr>
    
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('admin.projects.index')}}" class="btn btn-primary">Torna alla lista</a>
    
        <div class="d-flex align-items-center gap-2">
            <button type="reset" class="btn btn-secondary"><i class="fas fa-eraser me-2"></i>Svuota i campi</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-floppy-disk me-2"></i> Salva</button>
        </div>
    </div>
    
    </form>