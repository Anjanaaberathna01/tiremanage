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

    // Approval level constants
    public const LEVEL_SECTION_MANAGER = 1;
    public const LEVEL_MECHANIC_OFFICER = 2;
    public const LEVEL_TRANSPORT_OFFICER = 3;
    public const LEVEL_FINISHED = 0;

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public function request()
    {
        return $this->belongsTo(TireRequest::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}