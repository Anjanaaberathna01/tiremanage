<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'approvals';

    protected $fillable = [
        'request_id',
        'approved_by',
        'level',
        'status',
        'remarks',
    ];

    public function request()
    {
        return $this->belongsTo(TireRequest::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
