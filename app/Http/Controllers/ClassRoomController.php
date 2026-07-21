<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::with(['course', 'lecturer.user', 'academicYear'])->latest()->paginate(10);
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $courses = Course::orderBy('name')->get();
        $lecturers = Lecturer::with('user')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        return view('classes.create', compact('courses', 'lecturers', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_name' => 'required|string|max:10',
            'capacity' => 'required|integer|min:1|max:200',
        ]);

        ClassRoom::create($request->only('course_id', 'lecturer_id', 'academic_year_id', 'class_name', 'capacity'));

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(ClassRoom $class)
    {
        $courses = Course::orderBy('name')->get();
        $lecturers = Lecturer::with('user')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        return view('classes.edit', compact('class', 'courses', 'lecturers', 'academicYears'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_name' => 'required|string|max:10',
            'capacity' => 'required|integer|min:1|max:200',
        ]);

        $class->update($request->only('course_id', 'lecturer_id', 'academic_year_id', 'class_name', 'capacity'));

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $class)
    {
        $enrollmentCount = $class->enrollments()->count();

        if ($enrollmentCount > 0) {
            return redirect()->route('classes.index')->with('error', "Tidak bisa hapus, kelas ini masih memiliki {$enrollmentCount} mahasiswa terdaftar.");
        }

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
    }
}