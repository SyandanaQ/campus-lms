<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['year', 'semester', 'is_active'];

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }
}