<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBook extends Model
{
    use HasFactory;
    public function catagory()
    {
        return $this->hasOne(bookCatagory::class,'catagory_id');
    }
    public function subcatagory()
    {
        return $this->hasOne(bookSubCatagory::class,'subcatagory_id');
    }
    public function book()
    {
        return $this->hasOne(book::class,'book_id');
    }
    public function student()
    {
        return $this->hasOne(student::class,'student_id');
    }
    public function classes()
    {
        return $this->hasOne(classes::class,'class_id');
    }
    public function staff()
    {
        return $this->hasOne(staff::class,'staff_id');
    }
}
