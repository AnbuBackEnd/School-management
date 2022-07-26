<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class result extends Model
{
    use HasFactory;
    public function exam()
    {
        return $this->belongsTo(exam::class,'exam_id');
    }
    public function _class()
    {
        return $this->belongsTo(classes::class,'class_id');
    }
    public function subject()
    {
        return $this->belongsTo(subject::class,'subject_id');
    }
    public function student()
    {
        return $this->belongsTo(student::class,'student_id');
    }
}
