<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'studyProgram'])->latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $studyPrograms = StudyProgram::orderBy('name')->get();
        return view('students.create', compact('studyPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|max:20|unique:students,nim',
            'study_program_id' => 'required|exists:study_programs,id',
            'angkatan' => 'required|digits:4|integer|min:2000',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('mahasiswa');

        Student::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'study_program_id' => $request->study_program_id,
            'angkatan' => $request->angkatan,
        ]);

        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil ditambahkan. Password default: password123');
    }

    public function edit(Student $student)
    {
        $studyPrograms = StudyProgram::orderBy('name')->get();
        return view('students.edit', compact('student', 'studyPrograms'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($student->user_id)],
            'nim' => ['required', 'string', 'max:20', Rule::unique('students', 'nim')->ignore($student->id)],
            'study_program_id' => 'required|exists:study_programs,id',
            'angkatan' => 'required|digits:4|integer|min:2000',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $student->update([
            'nim' => $request->nim,
            'study_program_id' => $request->study_program_id,
            'angkatan' => $request->angkatan,
        ]);

        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $enrollmentCount = $student->enrollments()->count();

        if ($enrollmentCount > 0) {
            return redirect()->route('students.index')->with('error', "Tidak bisa hapus, mahasiswa ini masih memiliki {$enrollmentCount} data KRS.");
        }

        $student->user->delete();

        return redirect()->route('students.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}