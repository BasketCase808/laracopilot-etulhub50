<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandLog extends Model
{
    protected $fillable = [
        'user_id',
        'command',
        'parameters',
        'result',
        'status',
        'error_message',
        'executed_at'
    ];
    
    protected $casts = [
        'executed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}