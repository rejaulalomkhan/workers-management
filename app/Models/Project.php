<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function categories()
    {
        return $this->hasMany(ProjectCategory::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
