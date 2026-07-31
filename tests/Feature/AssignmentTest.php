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
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
    Storage::fake('public');
});

function makeClassForAssignment(): ClassRoom
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

function makeEnrolledStudent(ClassRoom $class): array
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

test('dosen bisa buat tugas untuk kelasnya', function () {
    $class = makeClassForAssignment();
    $lecturerUser = $class->lecturer->user;

    $response = $this->actingAs($lecturerUser)->post("/kelas-saya/{$class->id}/tugas", [
        'title' => 'Tugas 1',
        'description' => 'Kerjakan soal berikut',
        'deadline' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'weight' => 20,
    ]);

    $response->assertRedirect(route('assignments.index', $class));
    $this->assertDatabaseHas('assignments', [
        'class_id' => $class->id,
        'title' => 'Tugas 1',
    ]);
});

test('mahasiswa bisa submit tugas sebelum deadline', function () {
    $class = makeClassForAssignment();
    [$studentUser, $student] = makeEnrolledStudent($class);

    $assignment = Assignment::create([
        'class_id' => $class->id,
        'title' => 'Tugas 1',
        'deadline' => now()->addDays(3),
        'weight' => 20,
    ]);

    $file = UploadedFile::fake()->create('jawaban.pdf', 100);

    $response = $this->actingAs($studentUser)->post("/tugas/{$assignment->id}/submit", [
        'file' => $file,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('assignment_submissions', [
        'assignment_id' => $assignment->id,
        'student_id' => $student->id,
    ]);
});

test('mahasiswa tidak bisa submit tugas setelah deadline lewat', function () {
    $class = makeClassForAssignment();
    [$studentUser, $student] = makeEnrolledStudent($class);

    $assignment = Assignment::create([
        'class_id' => $class->id,
        'title' => 'Tugas Terlambat',
        'deadline' => now()->subDay(),
        'weight' => 20,
    ]);

    $file = UploadedFile::fake()->create('jawaban.pdf', 100);

    $this->actingAs($studentUser)->post("/tugas/{$assignment->id}/submit", [
        'file' => $file,
    ]);

    $this->assertDatabaseMissing('assignment_submissions', [
        'assignment_id' => $assignment->id,
        'student_id' => $student->id,
    ]);
});

test('mahasiswa bisa submit ulang, file lama tergantikan bukan duplikat', function () {
    $class = makeClassForAssignment();
    [$studentUser, $student] = makeEnrolledStudent($class);

    $assignment = Assignment::create([
        'class_id' => $class->id,
        'title' => 'Tugas 1',
        'deadline' => now()->addDays(3),
        'weight' => 20,
    ]);

    $file1 = UploadedFile::fake()->create('jawaban-v1.pdf', 100);
    $this->actingAs($studentUser)->post("/tugas/{$assignment->id}/submit", ['file' => $file1]);

    $firstSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
        ->where('student_id', $student->id)
        ->first();
    $firstFilePath = $firstSubmission->file_path;

    $file2 = UploadedFile::fake()->create('jawaban-v2.pdf', 100);
    $this->actingAs($studentUser)->post("/tugas/{$assignment->id}/submit", ['file' => $file2]);

    $count = AssignmentSubmission::where('assignment_id', $assignment->id)
        ->where('student_id', $student->id)
        ->count();

    expect($count)->toBe(1);

    $latestSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
        ->where('student_id', $student->id)
        ->first();

    expect($latestSubmission->file_path)->not->toBe($firstFilePath);
    Storage::disk('public')->assertMissing($firstFilePath);
});

test('dosen bisa beri nilai dan feedback ke submisi', function () {
    $class = makeClassForAssignment();
    [$studentUser, $student] = makeEnrolledStudent($class);
    $lecturerUser = $class->lecturer->user;

    $assignment = Assignment::create([
        'class_id' => $class->id,
        'title' => 'Tugas 1',
        'deadline' => now()->addDays(3),
        'weight' => 20,
    ]);

    $submission = AssignmentSubmission::create([
        'assignment_id' => $assignment->id,
        'student_id' => $student->id,
        'file_path' => 'submissions/dummy.pdf',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($lecturerUser)->put("/submisi/{$submission->id}/nilai", [
        'score' => 85,
        'feedback' => 'Bagus, tapi perlu perbaikan di bagian akhir.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('assignment_submissions', [
        'id' => $submission->id,
        'score' => 85,
        'feedback' => 'Bagus, tapi perlu perbaikan di bagian akhir.',
    ]);
});

test('mahasiswa yang belum krs tidak bisa submit tugas', function () {
    $class = makeClassForAssignment();

    $outsiderUser = User::create([
        'name' => 'Mahasiswa Luar',
        'email' => 'outsider' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $outsiderUser->assignRole('mahasiswa');
    Student::create([
        'user_id' => $outsiderUser->id,
        'nim' => '2024' . rand(1000, 9999),
        'study_program_id' => $class->course->study_program_id,
        'angkatan' => 2024,
    ]);

    $assignment = Assignment::create([
        'class_id' => $class->id,
        'title' => 'Tugas 1',
        'deadline' => now()->addDays(3),
        'weight' => 20,
    ]);

    $file = UploadedFile::fake()->create('jawaban.pdf', 100);

    $response = $this->actingAs($outsiderUser)->post("/tugas/{$assignment->id}/submit", [
        'file' => $file,
    ]);

    $response->assertStatus(403);
});