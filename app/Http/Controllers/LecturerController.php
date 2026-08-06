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

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-dosen.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'nidn', 'study_program']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function showImport()
    {
        return view('lecturers.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $filePath = $request->file('file')->getRealPath();
        $firstLine = fgets(fopen($filePath, 'r'));
        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file, 0, $delimiter);

        $success = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            $rowNumber++;

            if (count($row) < 4) {
                $errors[] = "Baris {$rowNumber}: kolom tidak lengkap.";
                continue;
            }

            [$name, $email, $nidn, $studyProgramName] = $row;

            if (User::where('email', $email)->exists()) {
                $errors[] = "Baris {$rowNumber}: email '{$email}' sudah terdaftar.";
                continue;
            }

            if (Lecturer::where('nidn', $nidn)->exists()) {
                $errors[] = "Baris {$rowNumber}: NIDN '{$nidn}' sudah terdaftar.";
                continue;
            }

            $studyProgram = StudyProgram::where('name', trim($studyProgramName))->first();

            if (!$studyProgram) {
                $errors[] = "Baris {$rowNumber}: program studi '{$studyProgramName}' tidak ditemukan.";
                continue;
            }

            $user = User::create([
                'name' => trim($name),
                'email' => trim($email),
                'password' => Hash::make('password123'),
            ]);
            $user->assignRole('dosen');

            Lecturer::create([
                'user_id' => $user->id,
                'nidn' => trim($nidn),
                'study_program_id' => $studyProgram->id,
            ]);

            $success++;
        }

        fclose($file);

        return redirect()->route('lecturers.index')->with('success', "Import selesai: {$success} berhasil, " . count($errors) . " gagal.")
            ->with('import_errors', $errors);
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