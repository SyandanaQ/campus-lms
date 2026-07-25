<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::global()->latest()->get();

        return view('admin-announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Announcement::create([
            'class_id' => null,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('admin-announcements.index')->with('success', 'Pengumuman global berhasil dibuat.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->class_id !== null) {
            abort(403);
        }

        $announcement->delete();

        return redirect()->route('admin-announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}