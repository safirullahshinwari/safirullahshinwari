<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'ticket_id', 'title', 'priority', 'message', 'status','assignee_id','department_id','assign_To_id'
    ];
    public function category()
    {
        return $this->belongsTo(category::class);
    }
    public function comments()
    {
        return $this->hasMany(comment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(department::class);
    }
}