<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book extends Model
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
}
