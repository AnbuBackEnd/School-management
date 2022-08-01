<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fee extends Model
{
    use HasFactory;
    public function classes()
    {
        return $this->belongsTO(Classes::class,'class_id');
    }
    public function feescatagory()
    {
        return $this->belongsTO(FeesStructureCatagory::class,'fees_catagory_id');
    }
    // public function classesMany()
    // {
    //     return $this->hasMany(Classes::class,'class_id');
    // }
    // public function feescatagoryMany()
    // {
    //     return $this->hasMany(FeesStructureCatagory::class,'fees_catagory_id');
    // }
}
