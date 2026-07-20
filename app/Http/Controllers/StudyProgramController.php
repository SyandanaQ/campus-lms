<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Models\Faculty;
use Illuminate\Http\Request;

class StudyProgramController extends Controller
{
    public function index()
    {
        $studyPrograms = StudyProgram::with('faculty')->latest()->paginate(10);
        return view('study-programs.index', compact('studyPrograms'));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('study-programs.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
            'level' => 'required|string|max:10',
        ]);

        StudyProgram::create($request->only('name', 'faculty_id', 'level'));

        return redirect()->route('study-programs.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(StudyProgram $studyProgram)
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('study-programs.edit', compact('studyProgram', 'faculties'));
    }

    public function update(Request $request, StudyProgram $studyProgram)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
            'level' => 'required|string|max:10',
        ]);

        $studyProgram->update($request->only('name', 'faculty_id', 'level'));

        return redirect()->route('study-programs.index')->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(StudyProgram $studyProgram)
    {
        $studyProgram->delete();

        return redirect()->route('study-programs.index')->with('success', 'Program Studi berhasil dihapus.');
    }
}