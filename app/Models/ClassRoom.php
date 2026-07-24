<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $table = 'classes';

    protected $fillable = ['course_id', 'lecturer_id', 'academic_year_id', 'class_name', 'capacity'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'class_id')->orderBy('order');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }
}