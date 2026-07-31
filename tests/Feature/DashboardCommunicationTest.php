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
use App\Models\Announcement;
use App\Models\ForumThread;
use App\Models\ForumComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

function makeClassForCommunication(): ClassRoom
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

function makeEnrolledStudentForCommunication(ClassRoom $class): array
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

// --- Dashboard ---

test('dashboard admin menampilkan statistik yang akurat', function () {
    $admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $admin->assignRole('admin');

    $class = makeClassForCommunication();
    makeEnrolledStudentForCommunication($class);
    makeEnrolledStudentForCommunication($class);

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertOk();
    $response->assertViewHas('totalStudents', 2);
    $response->assertViewHas('totalClasses', 1);
});

// --- Pengumuman ---

test('mahasiswa melihat gabungan pengumuman kelas dan global', function () {
    $class = makeClassForCommunication();
    [$studentUser, $student] = makeEnrolledStudentForCommunication($class);

    $admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $admin->assignRole('admin');

    Announcement::create([
        'class_id' => $class->id,
        'user_id' => $class->lecturer->user_id,
        'title' => 'Pengumuman Kelas',
        'body' => 'Isi pengumuman kelas',
    ]);

    Announcement::create([
        'class_id' => null,
        'user_id' => $admin->id,
        'title' => 'Pengumuman Global',
        'body' => 'Isi pengumuman global',
    ]);

    $response = $this->actingAs($studentUser)->get('/pengumuman-saya');

    $response->assertOk();
    $response->assertViewHas('announcements', function ($announcements) {
        return $announcements->count() === 2;
    });
});

test('pembuat pengumuman bisa hapus miliknya sendiri', function () {
    $class = makeClassForCommunication();
    $lecturerUser = $class->lecturer->user;

    $announcement = Announcement::create([
        'class_id' => $class->id,
        'user_id' => $lecturerUser->id,
        'title' => 'Pengumuman Test',
        'body' => 'Isi',
    ]);

    $response = $this->actingAs($lecturerUser)->delete("/pengumuman/{$announcement->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
});

// --- Forum ---

test('dosen dan mahasiswa bisa saling balas komentar di kelas yang sama', function () {
    $class = makeClassForCommunication();
    [$studentUser, $student] = makeEnrolledStudentForCommunication($class);
    $lecturerUser = $class->lecturer->user;

    $threadResponse = $this->actingAs($studentUser)->post("/kelas/{$class->id}/forum", [
        'title' => 'Pertanyaan',
        'body' => 'Bagaimana cara mengerjakan tugas 1?',
    ]);

    $thread = ForumThread::where('title', 'Pertanyaan')->first();
    expect($thread)->not->toBeNull();

    $commentResponse = $this->actingAs($lecturerUser)->post("/forum/{$thread->id}/komentar", [
        'body' => 'Silakan lihat materi minggu 1',
    ]);

    $commentResponse->assertRedirect();
    $this->assertDatabaseHas('forum_comments', [
        'thread_id' => $thread->id,
        'user_id' => $lecturerUser->id,
    ]);
});

test('user tidak bisa akses forum kelas yang bukan miliknya', function () {
    $class = makeClassForCommunication();

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

    $response = $this->actingAs($outsiderUser)->get("/kelas/{$class->id}/forum");

    $response->assertStatus(403);
});
