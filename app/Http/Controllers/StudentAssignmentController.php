<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentAssignmentController extends Controller
{
    protected function authorizeClass(ClassRoom $class)
    {
        $student = Auth::user()->student;
        $isEnrolled = $class->enrollments()->where('student_id', $student->id)->exists();
        if (!$isEnrolled) {
            abort(403);
        }
    }

    public function index(ClassRoom $class)
    {
        $this->authorizeClass($class);

        $student = Auth::user()->student;
        $assignments = $class->assignments()->latest()->get();

        $mySubmissions = AssignmentSubmission::where('student_id', $student->id)
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        return view('student-assignments.index', compact('class', 'assignments', 'mySubmissions'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $this->authorizeClass($assignment->classRoom);

        if ($assignment->isPastDeadline()) {
            return redirect()->back()->with('error', 'Tidak bisa submit, deadline sudah lewat.');
        }

        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $student = Auth::user()->student;

        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
        }

        $path = $request->file('file')->store('submissions', 'public');

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            ['file_path' => $path, 'submitted_at' => now()]
        );

        return redirect()->back()->with('success', 'Tugas berhasil disubmit.');
    }
}