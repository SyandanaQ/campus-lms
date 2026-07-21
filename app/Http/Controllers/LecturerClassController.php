<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class LecturerClassController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        $classes = ClassRoom::with(['course', 'academicYear', 'enrollments'])
            ->where('lecturer_id', $lecturer->id)
            ->latest()
            ->get();

        return view('lecturer-classes.index', compact('classes'));
    }

    public function show(ClassRoom $class)
    {
        $lecturer = Auth::user()->lecturer;

        if ($class->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $class->load(['course', 'academicYear', 'enrollments.student.user']);

        return view('lecturer-classes.show', compact('class'));
    }
}