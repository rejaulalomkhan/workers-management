<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerCategory extends Model
{
    protected $fillable = ['name', 'status'];

    public function workers()
    {
        return $this->hasMany(Worker::class, 'worker_category_id');
    }
}
