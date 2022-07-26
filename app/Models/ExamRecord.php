<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRecord extends Model
{
    use HasFactory;
    public function exam()
    {
        $this->belongsTo(exam::class,'exam_id');
    }
    public function class_()
    {
        $this->belongsTo(classes::class,'class_id');
    }
    public function subject()
    {
        $this->belongsTo(subject::class,'subject_id');
    }
}
