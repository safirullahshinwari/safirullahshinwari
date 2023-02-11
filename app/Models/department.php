<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    protected $fillable = ['name'];
    public function users()
    {
        return $this->hasMany(users::class);
    }
}