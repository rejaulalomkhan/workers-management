<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function rates()
    {
        return $this->hasMany(WorkerRate::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
