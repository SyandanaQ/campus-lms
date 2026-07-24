<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LecturerMaterialController extends Controller
{
    protected function authorizeClass(ClassRoom $class)
    {
        $lecturer = Auth::user()->lecturer;
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403);
        }
    }

    public function index(ClassRoom $class)
    {
        $this->authorizeClass($class);

        $materials = $class->materials;

        return view('lecturer-materials.index', compact('class', 'materials'));
    }

    public function store(Request $request, ClassRoom $class)
    {
        $this->authorizeClass($class);

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,youtube,link',
            'file' => 'required_if:type,pdf|file|mimes:pdf|max:10240',
            'url' => 'required_if:type,youtube,link|nullable|url',
        ]);

        $data = [
            'class_id' => $class->id,
            'title' => $request->title,
            'type' => $request->type,
            'order' => $class->materials()->count() + 1,
        ];

        if ($request->type === 'pdf') {
            $path = $request->file('file')->store('materials', 'public');
            $data['file_path'] = $path;
        } else {
            $data['url'] = $request->url;
        }

        Material::create($data);

        return redirect()->route('materials.index', $class)->with('success', 'Materi berhasil ditambahkan.');
    }

    public function destroy(Material $material)
    {
        $this->authorizeClass($material->classRoom);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $classId = $material->class_id;
        $material->delete();

        return redirect()->route('materials.index', $classId)->with('success', 'Materi berhasil dihapus.');
    }
}