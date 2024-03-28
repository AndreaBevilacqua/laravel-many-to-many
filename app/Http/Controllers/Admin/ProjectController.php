<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\Type;
use App\Models\Technology;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderByDesc('updated_at')->orderByDesc('created_at')->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        $types = Type::select('label', 'id')->get();
        $technologies = Technology::select('label', 'id')->get();
        return view('admin.projects.create', compact('project', 'types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:5|max:50|unique:projects',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'is_published' => 'nullable|boolean',
            'type_id' => 'nullable|exists:types,id',
            'technologies' => 'nullable|exists:technologies,id'

        ], [
            'title.required' => 'Il titolo é obbligatorio',
            'title.min' => 'Il titolo deve esssere almeno :min caratteri',
            'title.max' => 'Il titolo deve esssere almeno :max caratteri',
            'title.unique' => 'Non possono esister due proggetti con lo stesso titolo',
            'image.image' => 'Il file inserito non é un\immagine',
            'image.mimes' => 'Le estensioni valide sono :mimes',
            'is_published.coolean' => 'Il valore del campo non é valido',
            'content.required' => 'Il contenuto é obbligatorio',
            'type_id.exists' => 'Tipo non valido',
            'technologies.exists' => 'Tecnologia selezionata non valida'
        ]);

        $data = $request->all();

        $project = new Project();

        $project->fill($data);
        $project->slug = Str::slug($project->title);
        $project->is_published = array_key_exists('is_published', $data);

        // Controllo se mi arriva un file
        if(Arr::exists($data, 'image')){
            $extension = $data['image']->extension();

            // Lo salvo e prendo l'url
            $img_url = Storage::putFileAs('project_images', $data['image'], "$project->slug.$extension");
            $project->image = $img_url;
        }

        $project->save();

        if(Arr::exists($data, 'technologies')){
            $project->technologies()->attach($data['technologies']);
        }

        return to_route('admin.projects.show', $project)->with('message', 'Proggetto creato con successo')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        
        $types = Type::select('label', 'id')->get();
        $technologies = Technology::select('label', 'id')->get();
        $prev_technologies = $project->$technologies->pluck('id')->toArray();
        return view('admin.projects.edit', compact('project', 'types', 'technologies', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:50', Rule::unique('projects')->ignore($project->id)],
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'is_published' => 'nullable|boolean',
            'type_id' => 'nullable|exists:types,id',
            'technologies' => 'nullable|exists:technologies,id'
        ], [
            'title.required' => 'Il titolo é obbligatorio',
            'title.min' => 'Il titolo deve esssere almeno :min caratteri',
            'title.max' => 'Il titolo deve esssere almeno :max caratteri',
            'title.unique' => 'Non possono esister due proggetti con lo stesso titolo',
            'image.image' => 'Il file inserito non é un\immagine',
            'image.mimes' => 'Le estensioni valide sono :mimes',
            'is_published.coolean' => 'Il valore del campo non é valido',
            'content.required' => 'Il contenuto é obbligatorio',
            'type_id.exists' => 'Tipo non valido',
            'technologies.exists' => 'Tecnologia selezionata non valida'
        ]);
        
        $data = $request->all();

        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = array_key_exists('is_published', $data);

        // Controllo se mi arriva un file
        if(Arr::exists($data, 'image')){
            // Controllo se aveva giá una immagine
            if($project->image) Storage::delete($project->image);

            $extension = $data['image']->extension();

            // Lo salvo e prendo l'url
            $img_url = Storage::putFileAs('project_images', $data['image'], "{$data['slug']}.$extension");
            $project->image = $img_url;
        }

        $project->update($data);

        if(Arr::exists($data, 'technologies')) $project->technologies()->sync($data['technologies']);
        elseif(!Arr::exists($data, 'technologies') && $project->has('technologies')) $project->technologies()->detath();
        

        return to_route('admin.projects.show', $project)->with('message', 'Proggetto modificato con successo')->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index')->with('type', 'danger')->with('message', 'Progetto eliminato');
    }
}
