<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerAssignmentController extends Controller
{
    protected function authorizeClass(ClassRoom $class)
    {
        $lecturer = Auth::user()->lecturer;
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403);
        }
    }

    protected function authorizeAssignment(Assignment $assignment)
    {
        $this->authorizeClass($assignment->classRoom);
    }

    public function index(ClassRoom $class)
    {
        $this->authorizeClass($class);

        $assignments = $class->assignments()->latest()->get();

        return view('lecturer-assignments.index', compact('class', 'assignments'));
    }

    public function store(Request $request, ClassRoom $class)
    {
        $this->authorizeClass($class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'weight' => 'required|integer|min:0|max:100',
        ]);

        Assignment::create([
            'class_id' => $class->id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'weight' => $request->weight,
        ]);

        return redirect()->route('assignments.index', $class)->with('success', 'Tugas berhasil dibuat.');
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $classId = $assignment->class_id;
        $assignment->delete();

        return redirect()->route('assignments.index', $classId)->with('success', 'Tugas berhasil dihapus.');
    }

    public function submissions(Assignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $submissions = $assignment->submissions()->with('student.user')->get();

        return view('lecturer-assignments.submissions', compact('assignment', 'submissions'));
    }

    public function grade(Request $request, AssignmentSubmission $submission)
    {
        $this->authorizeAssignment($submission->assignment);

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('assignments.submissions', $submission->assignment)->with('success', 'Nilai berhasil disimpan.');
    }
}