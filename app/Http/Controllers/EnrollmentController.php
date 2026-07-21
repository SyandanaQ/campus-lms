<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function available()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $student = Auth::user()->student;

        $classes = collect();

        if ($activeYear) {
            $classes = ClassRoom::with(['course', 'lecturer.user', 'enrollments'])
                ->where('academic_year_id', $activeYear->id)
                ->get();
        }

        $enrolledClassIds = $student->enrollments()->pluck('class_id')->toArray();

        return view('enrollments.available', compact('classes', 'activeYear', 'enrolledClassIds'));
    }

    public function store(Request $request, ClassRoom $class)
    {
        $student = Auth::user()->student;

        $alreadyEnrolled = Enrollment::where('student_id', $student->id)
            ->where('class_id', $class->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->route('enrollments.available')->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        $currentCount = $class->enrollments()->count();

        if ($currentCount >= $class->capacity) {
            return redirect()->route('enrollments.available')->with('error', 'Kelas sudah penuh.');
        }

        Enrollment::create([
            'student_id' => $student->id,
            'class_id' => $class->id,
            'status' => 'aktif',
        ]);

        return redirect()->route('enrollments.available')->with('success', 'Berhasil mengambil KRS untuk ' . $class->course->name . '.');
    }

    public function myEnrollments()
    {
        $student = Auth::user()->student;

        $enrollments = Enrollment::with(['classRoom.course', 'classRoom.lecturer.user', 'classRoom.academicYear'])
            ->where('student_id', $student->id)
            ->get();

        return view('enrollments.my', compact('enrollments'));
    }

    public function destroy(Enrollment $enrollment)
    {
        $student = Auth::user()->student;

        if ($enrollment->student_id !== $student->id) {
            abort(403);
        }

        $enrollment->delete();

        return redirect()->route('enrollments.my')->with('success', 'KRS berhasil dibatalkan.');
    }
}