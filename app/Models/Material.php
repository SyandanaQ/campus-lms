<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['class_id', 'title', 'type', 'file_path', 'url', 'order'];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}