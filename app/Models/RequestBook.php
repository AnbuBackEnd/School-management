<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBook extends Model
{
    use HasFactory;
    public function catagory()
    {
        return $this->belongsTo(bookCatagory::class,'catagory_id');
    }
    public function subcatagory()
    {
        return $this->belongsTo(bookSubCatagory::class,'subcatagory_id');
    }
    public function book()
    {
        return $this->belongsTo(book::class,'book_id');
    }
    public function student()
    {
        return $this->belongsTo(student::class,'student_id');
    }
    public function classes()
    {
        return $this->belongsTo(classes::class,'class_id');
    }
    public function staff()
    {
        return $this->belongsTo(user::class,'staff_id');
    }
}
