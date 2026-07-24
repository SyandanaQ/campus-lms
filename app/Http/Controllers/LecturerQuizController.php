<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerQuizController extends Controller
{
    protected function authorizeClass(ClassRoom $class)
    {
        $lecturer = Auth::user()->lecturer;
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403);
        }
    }

    protected function authorizeQuiz(Quiz $quiz)
    {
        $this->authorizeClass($quiz->classRoom);
    }

    public function index(ClassRoom $class)
    {
        $this->authorizeClass($class);

        $quizzes = $class->quizzes()->withCount('questions')->latest()->get();

        return view('lecturer-quizzes.index', compact('class', 'quizzes'));
    }

    public function store(Request $request, ClassRoom $class)
    {
        $this->authorizeClass($class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz = Quiz::create([
            'class_id' => $class->id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('quizzes.manage', $quiz)->with('success', 'Kuis berhasil dibuat, sekarang tambahkan soal.');
    }

    public function manage(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        $questions = $quiz->questions()->with('options')->get();
        $isLocked = $quiz->attempts()->exists();

        return view('lecturer-quizzes.manage', compact('quiz', 'questions', 'isLocked'));
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        if ($quiz->attempts()->exists()) {
            return redirect()->route('quizzes.manage', $quiz)->with('error', 'Kuis sudah dikerjakan mahasiswa, tidak bisa ditambah soal lagi.');
        }

        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => $request->question_text,
            'order' => $quiz->questions()->count() + 1,
        ]);

        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => (int) $index === (int) $request->correct_option,
            ]);
        }

        return redirect()->route('quizzes.manage', $quiz)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function destroyQuestion(QuizQuestion $question)
    {
        $this->authorizeQuiz($question->quiz);

        if ($question->quiz->attempts()->exists()) {
            return redirect()->route('quizzes.manage', $question->quiz)->with('error', 'Kuis sudah dikerjakan mahasiswa, tidak bisa hapus soal lagi.');
        }

        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('quizzes.manage', $quizId)->with('success', 'Soal berhasil dihapus.');
    }
    public function results(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        $attempts = $quiz->attempts()->with('student.user')->get();

        return view('lecturer-quizzes.results', compact('quiz', 'attempts'));
    }
}