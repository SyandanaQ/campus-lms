<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\FinalGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerGradeController extends Controller
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

        $students = $class->enrollments()->with('student.user')->get()->pluck('student');

        $grades = FinalGrade::where('class_id', $class->id)
            ->get()
            ->keyBy('student_id');

        return view('lecturer-grades.index', compact('class', 'students', 'grades'));
    }

    public function store(Request $request, ClassRoom $class)
    {
        $this->authorizeClass($class);

        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->scores as $studentId => $score) {
            if ($score === null || $score === '') {
                continue;
            }

            FinalGrade::updateOrCreate(
                ['class_id' => $class->id, 'student_id' => $studentId],
                ['score' => $score, 'letter' => FinalGrade::scoreToLetter($score)]
            );
        }

        return redirect()->route('final-grades.index', $class)->with('success', 'Nilai akhir berhasil disimpan.');
    }

    public function export(ClassRoom $class)
    {
        $this->authorizeClass($class);

        $students = $class->enrollments()->with('student.user')->get()->pluck('student');
        $grades = FinalGrade::where('class_id', $class->id)->get()->keyBy('student_id');

        $filename = 'nilai-' . str_replace(' ', '-', strtolower($class->course->name)) . '-' . $class->class_name . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($students, $grades) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['NIM', 'Nama', 'Nilai', 'Huruf']);

            foreach ($students as $student) {
                $grade = $grades->get($student->id);
                fputcsv($file, [
                    $student->nim,
                    $student->user->name,
                    $grade->score ?? '-',
                    $grade->letter ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}