<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class StudentAnnouncementController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $classIds = $student->enrollments()->pluck('class_id');

        $announcements = Announcement::where(function ($query) use ($classIds) {
                $query->whereIn('class_id', $classIds)
                      ->orWhereNull('class_id');
            })
            ->with('classRoom.course')
            ->latest()
            ->get();

        return view('student-announcements.index', compact('announcements'));
    }
}