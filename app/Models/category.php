<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $fillable = ['name'];
    public function tickets()
    {
        return $this->hasMany(ticket::class);
    }
}