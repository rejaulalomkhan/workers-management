<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function booted()
    {
        static::addGlobalScope('orderById', function ($builder) {
            $builder->orderBy('id', 'asc');
        });
    }

    public function rates()
    {
        return $this->hasMany(WorkerRate::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments()
    {
        return $this->hasMany(WorkerPayment::class);
    }
    
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
    
    public function category()
    {
        return $this->belongsTo(WorkerCategory::class, 'worker_category_id');
    }
}
