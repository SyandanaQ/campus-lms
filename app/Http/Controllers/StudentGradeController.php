<?php

namespace App\Http\Controllers;

use App\Models\FinalGrade;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentGradeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $grades = $student->finalGrades()->with('classRoom.course', 'classRoom.academicYear')->get();

        $totalSks = 0;
        $totalPointXSks = 0;

        foreach ($grades as $grade) {
            $sks = $grade->classRoom->course->sks;
            $point = FinalGrade::letterToPoint($grade->letter);

            $totalSks += $sks;
            $totalPointXSks += ($point * $sks);
        }

        $ipk = $totalSks > 0 ? round($totalPointXSks / $totalSks, 2) : null;

        return view('student-grades.index', compact('grades', 'ipk', 'totalSks'));
    }

    public function exportPdf()
    {
        $student = Auth::user()->student;

        $grades = $student->finalGrades()->with('classRoom.course', 'classRoom.academicYear')->get();

        $totalSks = 0;
        $totalPointXSks = 0;

        foreach ($grades as $grade) {
            $sks = $grade->classRoom->course->sks;
            $point = FinalGrade::letterToPoint($grade->letter);

            $totalSks += $sks;
            $totalPointXSks += ($point * $sks);
        }

        $ipk = $totalSks > 0 ? round($totalPointXSks / $totalSks, 2) : 0;

        $pdf = Pdf::loadView('student-grades.pdf', [
            'student' => $student,
            'grades' => $grades,
            'totalSks' => $totalSks,
            'ipk' => $ipk,
        ]);

        return $pdf->download('transkrip-' . $student->nim . '.pdf');
    }
}