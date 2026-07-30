<?php

use App\Models\User;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

function makeClassForMaterial(): ClassRoom
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

test('dosen bisa tambah materi tipe link ke kelasnya', function () {
    $class = makeClassForMaterial();
    $lecturerUser = $class->lecturer->user;

    $response = $this->actingAs($lecturerUser)->post("/kelas-saya/{$class->id}/materi", [
        'title' => 'Slide Minggu 1',
        'type' => 'link',
        'url' => 'https://example.com/slide.pdf',
    ]);

    $response->assertRedirect(route('materials.index', $class));
    $this->assertDatabaseHas('materials', [
        'class_id' => $class->id,
        'title' => 'Slide Minggu 1',
        'type' => 'link',
    ]);
});

test('dosen lain tidak bisa tambah materi ke kelas yang bukan miliknya', function () {
    $class = makeClassForMaterial();

    $otherLecturerUser = User::create([
        'name' => 'Dosen Lain',
        'email' => 'other' . uniqid() . '@test.com',
        'password' => Hash::make('password'),
    ]);
    $otherLecturerUser->assignRole('dosen');
    Lecturer::create([
        'user_id' => $otherLecturerUser->id,
        'nidn' => (string) rand(1000000000, 9999999999),
        'study_program_id' => $class->course->study_program_id,
    ]);

    $response = $this->actingAs($otherLecturerUser)->post("/kelas-saya/{$class->id}/materi", [
        'title' => 'Materi Ilegal',
        'type' => 'link',
        'url' => 'https://example.com',
    ]);

    $response->assertStatus(403);
});