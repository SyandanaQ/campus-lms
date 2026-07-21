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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

function makeStudent(): array
{
    $faculty = Faculty::create(['name' => 'Fakultas Teknik']);
    $prodi = StudyProgram::create(['name' => 'Informatika', 'faculty_id' => $faculty->id, 'level' => 'S1']);

    $user = User::create([
        'name' => 'Mahasiswa Test',
        'email' => 'mhs' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $user->assignRole('mahasiswa');

    $student = Student::create([
        'user_id' => $user->id,
        'nim' => '2024' . rand(1000, 9999),
        'study_program_id' => $prodi->id,
        'angkatan' => 2024,
    ]);

    return [$user, $student, $prodi];
}

function makeClass($prodi, int $capacity = 40): ClassRoom
{
    $course = Course::create([
        'code' => 'CS' . rand(100, 999),
        'name' => 'Mata Kuliah Test',
        'sks' => 3,
        'study_program_id' => $prodi->id,
    ]);

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

    $academicYear = AcademicYear::create([
        'year' => '2025/2026',
        'semester' => 'ganjil',
        'is_active' => true,
    ]);

    return ClassRoom::create([
        'course_id' => $course->id,
        'lecturer_id' => $lecturer->id,
        'academic_year_id' => $academicYear->id,
        'class_name' => 'A',
        'capacity' => $capacity,
    ]);
}

test('mahasiswa tidak bisa akses halaman admin', function () {
    [$user] = makeStudent();

    $response = $this->actingAs($user)->get('/faculties');

    $response->assertStatus(403);
});

test('mahasiswa bisa enroll ke kelas yang tersedia', function () {
    [$user, $student, $prodi] = makeStudent();
    $class = makeClass($prodi);

    $response = $this->actingAs($user)->post("/kelas-tersedia/{$class->id}/ambil");

    $response->assertRedirect('/kelas-tersedia');
    $this->assertDatabaseHas('enrollments', [
        'student_id' => $student->id,
        'class_id' => $class->id,
    ]);
});

test('mahasiswa tidak bisa enroll dobel ke kelas yang sama', function () {
    [$user, $student, $prodi] = makeStudent();
    $class = makeClass($prodi);

    Enrollment::create(['student_id' => $student->id, 'class_id' => $class->id, 'status' => 'aktif']);

    $this->actingAs($user)->post("/kelas-tersedia/{$class->id}/ambil");

    expect(Enrollment::where('student_id', $student->id)->where('class_id', $class->id)->count())->toBe(1);
});

test('mahasiswa tidak bisa enroll ke kelas yang sudah penuh', function () {
    [$user, $student, $prodi] = makeStudent();
    $class = makeClass($prodi, capacity: 1);

    [$otherUser, $otherStudent] = makeStudent();
    Enrollment::create(['student_id' => $otherStudent->id, 'class_id' => $class->id, 'status' => 'aktif']);

    $this->actingAs($user)->post("/kelas-tersedia/{$class->id}/ambil");

    $this->assertDatabaseMissing('enrollments', [
        'student_id' => $student->id,
        'class_id' => $class->id,
    ]);
});

test('admin tidak bisa hapus kelas yang masih ada mahasiswa', function () {
    $admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $admin->assignRole('admin');

    [$studentUser, $student, $prodi] = makeStudent();
    $class = makeClass($prodi);
    Enrollment::create(['student_id' => $student->id, 'class_id' => $class->id, 'status' => 'aktif']);

    $this->actingAs($admin)->delete("/classes/{$class->id}");

    $this->assertDatabaseHas('classes', ['id' => $class->id]);
});

test('admin bisa crud fakultas', function () {
    $admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post('/faculties', ['name' => 'Fakultas Baru']);
    $response->assertRedirect('/faculties');
    $this->assertDatabaseHas('faculties', ['name' => 'Fakultas Baru']);

    $faculty = Faculty::where('name', 'Fakultas Baru')->first();

    $this->actingAs($admin)->put("/faculties/{$faculty->id}", ['name' => 'Fakultas Update']);
    $this->assertDatabaseHas('faculties', ['name' => 'Fakultas Update']);

    $this->actingAs($admin)->delete("/faculties/{$faculty->id}");
    $this->assertDatabaseMissing('faculties', ['id' => $faculty->id]);
});