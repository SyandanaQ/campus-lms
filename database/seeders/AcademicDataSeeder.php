<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\ClassRoom;
use App\Models\Enrollment;

class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        // Fakultas & Prodi
        $fasilkom = Faculty::firstOrCreate(['name' => 'Fakultas Ilmu Komputer']);
        $prodiIF = StudyProgram::firstOrCreate([
            'name' => 'Teknik Informatika',
            'faculty_id' => $fasilkom->id,
            'level' => 'S1',
        ]);
        $prodiSI = StudyProgram::firstOrCreate([
            'name' => 'Sistem Informasi',
            'faculty_id' => $fasilkom->id,
            'level' => 'S1',
        ]);

        // Tahun Ajaran Aktif
        $tahunAjaran = AcademicYear::firstOrCreate([
            'year' => '2025/2026',
            'semester' => 'ganjil',
        ], [
            'is_active' => true,
        ]);

        // Dosen
        $dosenUser1 = User::firstOrCreate(
            ['email' => 'dosen1@lms.test'],
            ['name' => 'Dr. Andi Wijaya', 'password' => Hash::make('password')]
        );
        $dosenUser1->assignRole('dosen');
        $dosen1 = Lecturer::firstOrCreate([
            'user_id' => $dosenUser1->id,
        ], [
            'nidn' => '0011122233',
            'study_program_id' => $prodiIF->id,
        ]);

        $dosenUser2 = User::firstOrCreate(
            ['email' => 'dosen2@lms.test'],
            ['name' => 'Siti Rahayu, M.Kom', 'password' => Hash::make('password')]
        );
        $dosenUser2->assignRole('dosen');
        $dosen2 = Lecturer::firstOrCreate([
            'user_id' => $dosenUser2->id,
        ], [
            'nidn' => '0022233344',
            'study_program_id' => $prodiIF->id,
        ]);

        // Mahasiswa
        $mahasiswaData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@lms.test', 'nim' => '2024001001'],
            ['name' => 'Citra Dewi', 'email' => 'citra@lms.test', 'nim' => '2024001002'],
            ['name' => 'Doni Pratama', 'email' => 'doni@lms.test', 'nim' => '2024001003'],
        ];

        $students = [];
        foreach ($mahasiswaData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole('mahasiswa');
            $students[] = Student::firstOrCreate([
                'nim' => $data['nim'],
            ], [
                'user_id' => $user->id,
                'study_program_id' => $prodiIF->id,
                'angkatan' => 2024,
            ]);
        }

        // Mata Kuliah
        $matkul1 = Course::firstOrCreate([
            'code' => 'IF101',
        ], [
            'name' => 'Pemrograman Dasar',
            'sks' => 3,
            'study_program_id' => $prodiIF->id,
        ]);

        $matkul2 = Course::firstOrCreate([
            'code' => 'IF102',
        ], [
            'name' => 'Basis Data',
            'sks' => 3,
            'study_program_id' => $prodiIF->id,
        ]);

        // Kelas (2 kelas paralel untuk matkul1, beda dosen)
        $kelasA = ClassRoom::firstOrCreate([
            'course_id' => $matkul1->id,
            'class_name' => 'A',
            'academic_year_id' => $tahunAjaran->id,
        ], [
            'lecturer_id' => $dosen1->id,
            'capacity' => 40,
        ]);

        $kelasB = ClassRoom::firstOrCreate([
            'course_id' => $matkul1->id,
            'class_name' => 'B',
            'academic_year_id' => $tahunAjaran->id,
        ], [
            'lecturer_id' => $dosen2->id,
            'capacity' => 40,
        ]);

        $kelasBasisData = ClassRoom::firstOrCreate([
            'course_id' => $matkul2->id,
            'class_name' => 'A',
            'academic_year_id' => $tahunAjaran->id,
        ], [
            'lecturer_id' => $dosen2->id,
            'capacity' => 35,
        ]);

        // Enrollment (semua mahasiswa masuk Kelas A Pemrograman Dasar)
        foreach ($students as $student) {
            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'class_id' => $kelasA->id,
            ], [
                'status' => 'aktif',
            ]);
        }
    }
}