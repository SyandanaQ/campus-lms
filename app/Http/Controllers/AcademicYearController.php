<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::latest()->paginate(10);
        return view('academic-years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('academic-years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'is_active' => 'nullable|boolean',
        ]);

        // Kalau tahun ajaran ini di-set aktif, matikan dulu semua yang lain
        if ($request->boolean('is_active')) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create([
            'year' => $request->year,
            'semester' => $request->semester,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'year' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_active')) {
            AcademicYear::where('id', '!=', $academicYear->id)->where('is_active', true)->update(['is_active' => false]);
        }

        $academicYear->update([
            'year' => $request->year,
            'semester' => $request->semester,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}