<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    public function section()
    {
        return $this->belongsTo(section::class,'section_id');
    }
    public function standard()
    {
        return $this->belongsTo(standard::class,'standard_id');
    }
}
