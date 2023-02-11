<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    protected $fillable = [
        'ticket_id', 'user_id', 'comment'
      ];
      public function ticket()
      {
          return $this->belongsTo(ticket::class);
      }
      public function user()
      {
          return $this->belongsTo(User::class);
      }
}