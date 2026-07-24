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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('faculties', FacultyController::class);
    Route::resource('study-programs', StudyProgramController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('lecturers', LecturerController::class);
    Route::resource('students', StudentController::class);
    Route::resource('classes', ClassRoomController::class)->parameters(['classes' => 'class']);
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/kelas-tersedia', [EnrollmentController::class, 'available'])->name('enrollments.available');
    Route::post('/kelas-tersedia/{class}/ambil', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('/krs-saya', [EnrollmentController::class, 'myEnrollments'])->name('enrollments.my');
    Route::delete('/krs-saya/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    Route::get('/krs-saya/{class}/materi', [StudentMaterialController::class, 'index'])->name('student-materials.index');
    Route::get('/krs-saya/{class}/tugas', [StudentAssignmentController::class, 'index'])->name('student-assignments.index');
    Route::post('/tugas/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('student-assignments.submit');
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
});
require __DIR__.'/auth.php';
