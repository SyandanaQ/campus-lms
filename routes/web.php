<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LecturerClassController;
use App\Http\Controllers\LecturerMaterialController;
use App\Http\Controllers\StudentMaterialController;
use App\Http\Controllers\LecturerAssignmentController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\LecturerQuizController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\LecturerGradeController;
use App\Http\Controllers\StudentGradeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LecturerAnnouncementController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\StudentAnnouncementController;
use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/kelas/{class}/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::post('/kelas/{class}/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{thread}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{thread}/komentar', [ForumController::class, 'storeComment'])->name('forum.comment');
    Route::delete('/forum/{thread}', [ForumController::class, 'destroyThread'])->name('forum.thread.destroy');
    Route::delete('/forum-komentar/{comment}', [ForumController::class, 'destroyComment'])->name('forum.comment.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('faculties', FacultyController::class);
    Route::resource('study-programs', StudyProgramController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('lecturers', LecturerController::class);
    Route::resource('students', StudentController::class);
    Route::resource('classes', ClassRoomController::class)->parameters(['classes' => 'class']);
    Route::get('/pengumuman-global', [AdminAnnouncementController::class, 'index'])->name('admin-announcements.index');
    Route::post('/pengumuman-global', [AdminAnnouncementController::class, 'store'])->name('admin-announcements.store');
    Route::delete('/pengumuman-global/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('admin-announcements.destroy');
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/kelas-tersedia', [EnrollmentController::class, 'available'])->name('enrollments.available');
    Route::post('/kelas-tersedia/{class}/ambil', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('/krs-saya', [EnrollmentController::class, 'myEnrollments'])->name('enrollments.my');
    Route::delete('/krs-saya/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    Route::get('/krs-saya/{class}/materi', [StudentMaterialController::class, 'index'])->name('student-materials.index');
    Route::get('/krs-saya/{class}/tugas', [StudentAssignmentController::class, 'index'])->name('student-assignments.index');
    Route::post('/tugas/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('student-assignments.submit');
    Route::get('/krs-saya/{class}/kuis', [StudentQuizController::class, 'index'])->name('student-quizzes.index');
    Route::get('/kuis/{quiz}/kerjakan', [StudentQuizController::class, 'show'])->name('student-quizzes.show');
    Route::post('/kuis/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('student-quizzes.submit');
    Route::get('/kuis/{quiz}/hasil-saya', [StudentQuizController::class, 'result'])->name('student-quizzes.result');
    Route::get('/nilai-saya', [StudentGradeController::class, 'index'])->name('student-grades.index');
    Route::get('/pengumuman-saya', [StudentAnnouncementController::class, 'index'])->name('student-announcements.index');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/kelas-saya', [LecturerClassController::class, 'index'])->name('lecturer-classes.index');
    Route::get('/kelas-saya/{class}', [LecturerClassController::class, 'show'])->name('lecturer-classes.show');
    Route::get('/kelas-saya/{class}/materi', [LecturerMaterialController::class, 'index'])->name('materials.index');
    Route::post('/kelas-saya/{class}/materi', [LecturerMaterialController::class, 'store'])->name('materials.store');
    Route::delete('/materi/{material}', [LecturerMaterialController::class, 'destroy'])->name('materials.destroy');
    Route::get('/kelas-saya/{class}/tugas', [LecturerAssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/kelas-saya/{class}/tugas', [LecturerAssignmentController::class, 'store'])->name('assignments.store');
    Route::delete('/tugas/{assignment}', [LecturerAssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('/tugas/{assignment}/submisi', [LecturerAssignmentController::class, 'submissions'])->name('assignments.submissions');
    Route::put('/submisi/{submission}/nilai', [LecturerAssignmentController::class, 'grade'])->name('submissions.grade');
    Route::get('/kelas-saya/{class}/kuis', [LecturerQuizController::class, 'index'])->name('quizzes.index');
    Route::post('/kelas-saya/{class}/kuis', [LecturerQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/kuis/{quiz}/kelola', [LecturerQuizController::class, 'manage'])->name('quizzes.manage');
    Route::post('/kuis/{quiz}/soal', [LecturerQuizController::class, 'storeQuestion'])->name('quiz-questions.store');
    Route::delete('/soal/{question}', [LecturerQuizController::class, 'destroyQuestion'])->name('quiz-questions.destroy');
    Route::get('/kuis/{quiz}/hasil', [LecturerQuizController::class, 'results'])->name('quizzes.results');
    Route::get('/kelas-saya/{class}/nilai', [LecturerGradeController::class, 'index'])->name('final-grades.index');
    Route::post('/kelas-saya/{class}/nilai', [LecturerGradeController::class, 'store'])->name('final-grades.store');
    Route::get('/kelas-saya/{class}/pengumuman', [LecturerAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/kelas-saya/{class}/pengumuman', [LecturerAnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/pengumuman/{announcement}', [LecturerAnnouncementController::class, 'destroy'])->name('announcements.destroy');
});
require __DIR__.'/auth.php';
