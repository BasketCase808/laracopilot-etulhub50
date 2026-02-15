<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'api_client_id',
        'txid',
        'address',
        'amount',
        'type',
        'status',
        'confirmations',
        'comment'
    ];
    
    protected $casts = [
        'amount' => 'decimal:8',
        'confirmations' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function client()
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }
}