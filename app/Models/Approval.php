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

    /** ---------------- LEVEL CONSTANTS ---------------- */
    public const LEVEL_SECTION_MANAGER = 1;
    public const LEVEL_MECHANIC_OFFICER = 2;
    public const LEVEL_TRANSPORT_OFFICER = 3;
    public const LEVEL_FINISHED = 0;

    /** ---------------- STATUS CONSTANTS ---------------- */
    public const STATUS_PENDING = 'pending';

    // Forwarded to Mechanic Officer
    public const STATUS_PENDING_MECHANIC = 'pending_mechanic';

    // Approved by Section Manager
    public const STATUS_APPROVED_BY_MANAGER = 'approved_by_manager';

    // Generic approved (for flexibility)
    public const STATUS_APPROVED = 'approved';

    // Mechanic Officer’s own approval/rejection
    public const STATUS_APPROVED_BY_MECHANIC = 'approved_by_mechanic';
    public const STATUS_REJECTED_BY_MECHANIC = 'rejected_by_mechanic';

    // Transport Officer’s own approval/rejection
public const STATUS_PENDING_TRANSPORT = 'pending_transport';
public const STATUS_APPROVED_BY_TRANSPORT = 'approved_by_transport';
public const STATUS_REJECTED_BY_TRANSPORT = 'rejected_by_transport';


    // Generic rejection
    public const STATUS_REJECTED = 'rejected';

    /** ---------------- RELATIONSHIP ---------------- */
    public function request()
    {
        return $this->belongsTo(TireRequest::class, 'request_id');
    }

}