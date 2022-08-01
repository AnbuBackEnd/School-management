<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public function studentAttendance()
    {
        return $this->hasMany(StudentAttendance::class,'student_id');
    }
    public function searching()
    {
        return $this->studentAttendance()->where('present',1);
    }
    // public function presentCount()
    // {
    //     $count = $this->searching()->w
    // }
}
