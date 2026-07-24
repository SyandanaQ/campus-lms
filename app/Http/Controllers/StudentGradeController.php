<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $grades = $student->finalGrades()->with('classRoom.course', 'classRoom.academicYear')->get();

        return view('student-grades.index', compact('grades'));
    }
}