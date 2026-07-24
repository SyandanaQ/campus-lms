<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class StudentMaterialController extends Controller
{
    public function index(ClassRoom $class)
    {
        $student = Auth::user()->student;

        $isEnrolled = $class->enrollments()->where('student_id', $student->id)->exists();

        if (!$isEnrolled) {
            abort(403);
        }

        $materials = $class->materials;

        return view('student-materials.index', compact('class', 'materials'));
    }
}