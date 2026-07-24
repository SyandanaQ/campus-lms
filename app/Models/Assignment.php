<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['class_id', 'title', 'description', 'deadline', 'weight'];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function isPastDeadline(): bool
    {
        return now()->greaterThan($this->deadline);
    }
}