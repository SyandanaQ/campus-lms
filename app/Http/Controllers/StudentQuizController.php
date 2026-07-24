<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentQuizController extends Controller
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
        $quizzes = $class->quizzes()->withCount('questions')->get();

        $myAttempts = QuizAttempt::where('student_id', $student->id)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');

        return view('student-quizzes.index', compact('class', 'quizzes', 'myAttempts'));
    }

    public function show(Quiz $quiz)
    {
        $this->authorizeClass($quiz->classRoom);

        $student = Auth::user()->student;

        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student-quizzes.result', $quiz)->with('error', 'Anda sudah mengerjakan kuis ini.');
        }

        $questions = $quiz->questions()->with('options')->get();

        return view('student-quizzes.show', compact('quiz', 'questions'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $this->authorizeClass($quiz->classRoom);

        $student = Auth::user()->student;

        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student-quizzes.result', $quiz)->with('error', 'Anda sudah mengerjakan kuis ini.');
        }

        $request->validate([
            'answers' => 'required|array',
        ]);

        $questions = $quiz->questions()->with('options')->get();
        $correctCount = 0;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'score' => 0,
            'submitted_at' => now(),
        ]);

        foreach ($questions as $question) {
            $selectedOptionId = $request->answers[$question->id] ?? null;

            if ($selectedOptionId) {
                QuizAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'option_id' => $selectedOptionId,
                ]);

                $selectedOption = $question->options->firstWhere('id', $selectedOptionId);
                if ($selectedOption && $selectedOption->is_correct) {
                    $correctCount++;
                }
            }
        }

        $totalQuestions = $questions->count();
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

        $attempt->update(['score' => $score]);

        return redirect()->route('student-quizzes.result', $quiz)->with('success', 'Kuis berhasil disubmit.');
    }

    public function result(Quiz $quiz)
    {
        $this->authorizeClass($quiz->classRoom);

        $student = Auth::user()->student;

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->with('answers.question', 'answers.option')
            ->first();

        if (!$attempt) {
            abort(404);
        }

        return view('student-quizzes.result', compact('quiz', 'attempt'));
    }
}