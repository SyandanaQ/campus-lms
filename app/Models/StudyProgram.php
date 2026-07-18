<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $fillable = ['name', 'faculty_id', 'level'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}