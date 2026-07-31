<?php

use App\Models\User;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\QuizAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

function makeClassForQuiz(): ClassRoom
{
    $faculty = Faculty::create(['name' => 'Fakultas Teknik']);
    $prodi = StudyProgram::create(['name' => 'Informatika', 'faculty_id' => $faculty->id, 'level' => 'S1']);
    $course = Course::create(['code' => 'CS101', 'name' => 'Test Course', 'sks' => 3, 'study_program_id' => $prodi->id]);

    $lecturerUser = User::create([
        'name' => 'Dosen Test',
        'email' => 'dsn' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $lecturerUser->assignRole('dosen');
    $lecturer = Lecturer::create([
        'user_id' => $lecturerUser->id,
        'nidn' => (string) rand(1000000000, 9999999999),
        'study_program_id' => $prodi->id,
    ]);

    $academicYear = AcademicYear::create(['year' => '2025/2026', 'semester' => 'ganjil', 'is_active' => true]);

    return ClassRoom::create([
        'course_id' => $course->id,
        'lecturer_id' => $lecturer->id,
        'academic_year_id' => $academicYear->id,
        'class_name' => 'A',
        'capacity' => 40,
    ]);
}

function makeEnrolledStudentForQuiz(ClassRoom $class): array
{
    $user = User::create([
        'name' => 'Mahasiswa Test',
        'email' => 'mhs' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $user->assignRole('mahasiswa');

    $student = Student::create([
        'user_id' => $user->id,
        'nim' => '2024' . rand(1000, 9999),
        'study_program_id' => $class->course->study_program_id,
        'angkatan' => 2024,
    ]);

    Enrollment::create(['student_id' => $student->id, 'class_id' => $class->id, 'status' => 'aktif']);

    return [$user, $student];
}

function makeQuizWithQuestions(ClassRoom $class, int $questionCount = 3): Quiz
{
    $quiz = Quiz::create(['class_id' => $class->id, 'title' => 'Kuis Test']);

    for ($i = 1; $i <= $questionCount; $i++) {
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => "Soal ke-{$i}",
            'order' => $i,
        ]);

        QuizOption::create(['question_id' => $question->id, 'option_text' => 'Jawaban Benar', 'is_correct' => true]);
        QuizOption::create(['question_id' => $question->id, 'option_text' => 'Jawaban Salah', 'is_correct' => false]);
    }

    return $quiz;
}

test('dosen bisa buat kuis dan tambah soal dengan pilihan jawaban', function () {
    $class = makeClassForQuiz();
    $lecturerUser = $class->lecturer->user;

    $quizResponse = $this->actingAs($lecturerUser)->post("/kelas-saya/{$class->id}/kuis", [
        'title' => 'Kuis 1',
        'description' => 'Kuis latihan',
    ]);

    $quiz = Quiz::where('title', 'Kuis 1')->first();
    expect($quiz)->not->toBeNull();

    $response = $this->actingAs($lecturerUser)->post("/kuis/{$quiz->id}/soal", [
        'question_text' => 'Apa ibu kota Indonesia?',
        'options' => ['Jakarta', 'Bandung', 'Surabaya'],
        'correct_option' => 0,
    ]);

    $response->assertRedirect(route('quizzes.manage', $quiz));
    $this->assertDatabaseHas('quiz_questions', ['quiz_id' => $quiz->id, 'question_text' => 'Apa ibu kota Indonesia?']);
    $this->assertDatabaseHas('quiz_options', ['option_text' => 'Jakarta', 'is_correct' => true]);
    $this->assertDatabaseHas('quiz_options', ['option_text' => 'Bandung', 'is_correct' => false]);
});

test('mahasiswa kerjakan kuis dan skor dihitung otomatis dengan benar', function () {
    $class = makeClassForQuiz();
    [$studentUser, $student] = makeEnrolledStudentForQuiz($class);
    $quiz = makeQuizWithQuestions($class, 3);

    $questions = $quiz->questions()->with('options')->get();

    // Jawab 2 dari 3 soal dengan benar
    $answers = [];
    foreach ($questions as $index => $question) {
        $correctOption = $question->options->firstWhere('is_correct', true);
        $wrongOption = $question->options->firstWhere('is_correct', false);

        // Soal terakhir sengaja dijawab salah
        $answers[$question->id] = $index < 2 ? $correctOption->id : $wrongOption->id;
    }

    $response = $this->actingAs($studentUser)->post("/kuis/{$quiz->id}/submit", [
        'answers' => $answers,
    ]);

    $response->assertRedirect(route('student-quizzes.result', $quiz));

    $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->first();

    expect($attempt)->not->toBeNull();
    expect((float) $attempt->score)->toEqualWithDelta(66.67, 0.01);
});

test('mahasiswa tidak bisa mengerjakan kuis yang sama dua kali', function () {
    $class = makeClassForQuiz();
    [$studentUser, $student] = makeEnrolledStudentForQuiz($class);
    $quiz = makeQuizWithQuestions($class, 2);

    $questions = $quiz->questions()->with('options')->get();
    $answers = [];
    foreach ($questions as $question) {
        $answers[$question->id] = $question->options->firstWhere('is_correct', true)->id;
    }

    $this->actingAs($studentUser)->post("/kuis/{$quiz->id}/submit", ['answers' => $answers]);

    $firstAttemptCount = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->count();
    expect($firstAttemptCount)->toBe(1);

    // Coba submit lagi
    $this->actingAs($studentUser)->post("/kuis/{$quiz->id}/submit", ['answers' => $answers]);

    $secondAttemptCount = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->count();
    expect($secondAttemptCount)->toBe(1);
});

test('dosen tidak bisa tambah soal setelah kuis dikerjakan mahasiswa', function () {
    $class = makeClassForQuiz();
    [$studentUser, $student] = makeEnrolledStudentForQuiz($class);
    $lecturerUser = $class->lecturer->user;
    $quiz = makeQuizWithQuestions($class, 2);

    $questions = $quiz->questions()->with('options')->get();
    $answers = [];
    foreach ($questions as $question) {
        $answers[$question->id] = $question->options->firstWhere('is_correct', true)->id;
    }
    $this->actingAs($studentUser)->post("/kuis/{$quiz->id}/submit", ['answers' => $answers]);

    $questionCountBefore = $quiz->questions()->count();

    $this->actingAs($lecturerUser)->post("/kuis/{$quiz->id}/soal", [
        'question_text' => 'Soal baru setelah terkunci',
        'options' => ['A', 'B'],
        'correct_option' => 0,
    ]);

    $questionCountAfter = $quiz->questions()->count();

    expect($questionCountAfter)->toBe($questionCountBefore);
});