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
use App\Models\FinalGrade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

function makeClassForGrade(): ClassRoom
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

function makeEnrolledStudentForGrade(ClassRoom $class): array
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

test('nilai huruf otomatis terkonversi sesuai rentang', function () {
    $class = makeClassForGrade();
    [$studentUser, $student] = makeEnrolledStudentForGrade($class);
    $lecturerUser = $class->lecturer->user;

    $this->actingAs($lecturerUser)->post("/kelas-saya/{$class->id}/nilai", [
        'scores' => [$student->id => 90],
    ]);

    $this->assertDatabaseHas('final_grades', [
        'class_id' => $class->id,
        'student_id' => $student->id,
        'letter' => 'A',
    ]);
});

test('dosen bisa update nilai yang sudah ada tanpa duplikat', function () {
    $class = makeClassForGrade();
    [$studentUser, $student] = makeEnrolledStudentForGrade($class);
    $lecturerUser = $class->lecturer->user;

    $this->actingAs($lecturerUser)->post("/kelas-saya/{$class->id}/nilai", [
        'scores' => [$student->id => 60],
    ]);

    $this->actingAs($lecturerUser)->post("/kelas-saya/{$class->id}/nilai", [
        'scores' => [$student->id => 95],
    ]);

    $count = FinalGrade::where('class_id', $class->id)->where('student_id', $student->id)->count();
    expect($count)->toBe(1);

    $this->assertDatabaseHas('final_grades', [
        'class_id' => $class->id,
        'student_id' => $student->id,
        'score' => 95,
        'letter' => 'A',
    ]);
});

test('mahasiswa hanya bisa lihat nilainya sendiri', function () {
    $class = makeClassForGrade();
    [$studentUser1, $student1] = makeEnrolledStudentForGrade($class);
    [$studentUser2, $student2] = makeEnrolledStudentForGrade($class);

    FinalGrade::create(['class_id' => $class->id, 'student_id' => $student1->id, 'score' => 80, 'letter' => 'B']);
    FinalGrade::create(['class_id' => $class->id, 'student_id' => $student2->id, 'score' => 55, 'letter' => 'C']);

    $response = $this->actingAs($studentUser1)->get('/nilai-saya');

    $response->assertOk();
    $response->assertViewHas('grades', function ($grades) use ($student1) {
        return $grades->count() === 1 && $grades->first()->student_id === $student1->id;
    });
});