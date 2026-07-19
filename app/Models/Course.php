<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'sks', 'study_program_id'];

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }
}