<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalary extends Model
{
    use HasFactory;
    public function getStaff()
    {
        return $this->belongsTo(user::class,'staff_id');
    }
}
