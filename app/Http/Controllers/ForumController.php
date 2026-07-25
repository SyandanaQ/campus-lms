<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ForumThread;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    protected function authorizeClass(ClassRoom $class)
    {
        $user = Auth::user();

        if ($user->hasRole('dosen')) {
            if ($class->lecturer_id !== $user->lecturer->id) {
                abort(403);
            }
        } elseif ($user->hasRole('mahasiswa')) {
            $isEnrolled = $class->enrollments()->where('student_id', $user->student->id)->exists();
            if (!$isEnrolled) {
                abort(403);
            }
        } else {
            abort(403);
        }
    }

    protected function authorizeThreadClass(ForumThread $thread)
    {
        $this->authorizeClass($thread->classRoom);
    }

    public function index(ClassRoom $class)
    {
        $this->authorizeClass($class);

        $threads = $class->forumThreads()->with('creator')->withCount('comments')->latest()->get();

        return view('forum.index', compact('class', 'threads'));
    }

    public function store(Request $request, ClassRoom $class)
    {
        $this->authorizeClass($class);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $thread = ForumThread::create([
            'class_id' => $class->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('forum.show', $thread)->with('success', 'Thread berhasil dibuat.');
    }

    public function show(ForumThread $thread)
    {
        $this->authorizeThreadClass($thread);

        $thread->load('creator', 'comments.creator');

        return view('forum.show', compact('thread'));
    }

    public function storeComment(Request $request, ForumThread $thread)
    {
        $this->authorizeThreadClass($thread);

        $request->validate([
            'body' => 'required|string',
        ]);

        ForumComment::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return redirect()->route('forum.show', $thread)->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroyThread(ForumThread $thread)
    {
        $this->authorizeThreadClass($thread);

        if ($thread->user_id !== Auth::id()) {
            abort(403);
        }

        $classId = $thread->class_id;
        $thread->delete();

        return redirect()->route('forum.index', $classId)->with('success', 'Thread berhasil dihapus.');
    }

    public function destroyComment(ForumComment $comment)
    {
        $this->authorizeThreadClass($comment->thread);

        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $threadId = $comment->thread_id;
        $comment->delete();

        return redirect()->route('forum.show', $threadId)->with('success', 'Komentar berhasil dihapus.');
    }
}