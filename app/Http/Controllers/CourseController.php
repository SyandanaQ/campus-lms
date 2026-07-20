<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyProgram;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('studyProgram')->latest()->paginate(10);
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $studyPrograms = StudyProgram::orderBy('name')->get();
        return view('courses.create', compact('studyPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:courses,code',
            'name' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'study_program_id' => 'required|exists:study_programs,id',
        ]);

        Course::create($request->only('code', 'name', 'sks', 'study_program_id'));

        return redirect()->route('courses.index')->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function edit(Course $course)
    {
        $studyPrograms = StudyProgram::orderBy('name')->get();
        return view('courses.edit', compact('course', 'studyPrograms'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'name' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'study_program_id' => 'required|exists:study_programs,id',
        ]);

        $course->update($request->only('code', 'name', 'sks', 'study_program_id'));

        return redirect()->route('courses.index')->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}