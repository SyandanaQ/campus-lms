<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::with(['user', 'studyProgram'])->latest()->paginate(10);
        return view('lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        $studyPrograms = StudyProgram::orderBy('name')->get();
        return view('lecturers.create', compact('studyPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nidn' => 'required|string|max:20|unique:lecturers,nidn',
            'study_program_id' => 'required|exists:study_programs,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('dosen');

        Lecturer::create([
            'user_id' => $user->id,
            'nidn' => $request->nidn,
            'study_program_id' => $request->study_program_id,
        ]);

        return redirect()->route('lecturers.index')->with('success', 'Dosen berhasil ditambahkan. Password default: password123');
    }

    public function edit(Lecturer $lecturer)
    {
        $studyPrograms = StudyProgram::orderBy('name')->get();
        return view('lecturers.edit', compact('lecturer', 'studyPrograms'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($lecturer->user_id)],
            'nidn' => ['required', 'string', 'max:20', Rule::unique('lecturers', 'nidn')->ignore($lecturer->id)],
            'study_program_id' => 'required|exists:study_programs,id',
        ]);

        $lecturer->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $lecturer->update([
            'nidn' => $request->nidn,
            'study_program_id' => $request->study_program_id,
        ]);

        return redirect()->route('lecturers.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy(Lecturer $lecturer)
    {
        $activeClassCount = $lecturer->classes()->count();

        if ($activeClassCount > 0) {
            return redirect()->route('lecturers.index')->with('error', "Tidak bisa hapus, dosen ini masih mengampu {$activeClassCount} kelas.");
        }

        $lecturer->user->delete(); // otomatis hapus Lecturer juga karena cascadeOnDelete

        return redirect()->route('lecturers.index')->with('success', 'Dosen berhasil dihapus.');
    }
}