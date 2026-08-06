<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Course;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\ClassRoom;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\FinalGrade;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('dosen')) {
            return $this->lecturerDashboard();
        } elseif ($user->hasRole('mahasiswa')) {
            return $this->studentDashboard();
        }

        return view('dashboard');
    }

    protected function adminDashboard()
    {
        $data = [
            'totalStudents' => Student::count(),
            'totalLecturers' => Lecturer::count(),
            'totalClasses' => ClassRoom::count(),
            'totalCourses' => Course::count(),
            'activeYear' => AcademicYear::where('is_active', true)->first(),
        ];

        return view('dashboard-admin', $data);
    }

    protected function lecturerDashboard()
    {
        $lecturer = Auth::user()->lecturer;
        $classes = ClassRoom::where('lecturer_id', $lecturer->id)
            ->with(['course', 'enrollments'])
            ->get();

        $classSummaries = $classes->map(function ($class) {
            $ungraded = AssignmentSubmission::whereIn('assignment_id', $class->assignments()->pluck('id'))
                ->whereNull('score')
                ->count();

            return [
                'class' => $class,
                'studentCount' => $class->enrollments->count(),
                'ungradedCount' => $ungraded,
                'materialCount' => $class->materials()->count(),
                'quizCount' => $class->quizzes()->count(),
            ];
        });

        return view('dashboard-lecturer', compact('classSummaries'));
    }

    protected function studentDashboard()
        {
            $student = Auth::user()->student;

            $classIds = $student->enrollments()->pluck('class_id');

            $assignments = Assignment::whereIn('class_id', $classIds)->get();
            $submittedCount = AssignmentSubmission::where('student_id', $student->id)
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->count();

            $quizzes = Quiz::whereIn('class_id', $classIds)->get();
            $quizDoneCount = QuizAttempt::where('student_id', $student->id)
                ->whereIn('quiz_id', $quizzes->pluck('id'))
                ->count();

            $grades = FinalGrade::where('student_id', $student->id)->with('classRoom.course')->get();

            $totalSks = 0;
            $totalPointXSks = 0;

            foreach ($grades as $grade) {
                $sks = $grade->classRoom->course->sks;
                $point = FinalGrade::letterToPoint($grade->letter);

                $totalSks += $sks;
                $totalPointXSks += ($point * $sks);
            }

            $ipk = $totalSks > 0 ? round($totalPointXSks / $totalSks, 2) : null;

            $data = [
                'classCount' => $classIds->count(),
                'assignmentTotal' => $assignments->count(),
                'assignmentSubmitted' => $submittedCount,
                'quizTotal' => $quizzes->count(),
                'quizDone' => $quizDoneCount,
                'ipk' => $ipk,
            ];

            return view('dashboard-student', $data);
        }
    }