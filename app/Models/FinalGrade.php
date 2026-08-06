<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalGrade extends Model
{
    protected $fillable = ['class_id', 'student_id', 'score', 'letter'];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public static function scoreToLetter(float $score): string
    {
        return match (true) {
            $score >= 85 => 'A',
            $score >= 70 => 'B',
            $score >= 55 => 'C',
            $score >= 40 => 'D',
            default => 'E',
        };
    }

    public static function letterToPoint(string $letter): float
    {
        return match ($letter) {
            'A' => 4.00,
            'B' => 3.00,
            'C' => 2.00,
            'D' => 1.00,
            default => 0.00,
        };
    }
}