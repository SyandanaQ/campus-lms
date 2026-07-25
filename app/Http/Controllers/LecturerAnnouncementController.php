<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerAnnouncementController extends Controller
{
    protected function authorizeClass(ClassRoom $class)
    {
        $lecturer = Auth::user()->lecturer;
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403);
        }
    }

    protected function authorizeAnnouncement(Announcement $announcement)
    {
        if ($announcement->user_id !== Auth::id()) {
            abort(403);
        }
    }

    public function index(ClassRoom $class)
    {
        $this->authorizeClass($class);

        $announcements = $class->announcements()->latest()->get();

        return view('lecturer-announcements.index', compact('class', 'announcements'));
    }

    public function store(Request $request, ClassRoom $class)
    {
        $this->authorizeClass($class);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Announcement::create([
            'class_id' => $class->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('announcements.index', $class)->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorizeAnnouncement($announcement);

        $classId = $announcement->class_id;
        $announcement->delete();

        return redirect()->route('announcements.index', $classId)->with('success', 'Pengumuman berhasil dihapus.');
    }
}