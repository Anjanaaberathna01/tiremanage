<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Approval;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Tire;
use App\Models\Driver;
use App\Models\Receipt;


class TireRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'driver_id',
        'branch',
        'tire_id',
        'tire_count',
        'damage_description',
        'tire_images',
        'status',
        'current_level', // workflow tracking
        'delivery_place_office',
        'delivery_place_street',
        'delivery_place_town',
        'last_tire_replacement_date',
        'existing_tire_make',
    ];

    protected $casts = [
        'tire_images' => 'array',
        'last_tire_replacement_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tire()
    {
        return $this->belongsTo(Tire::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'request_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
public function receipt()
{
    return $this->hasOne(Receipt::class, 'request_id');
}


    /*
    |--------------------------------------------------------------------------
    | Workflow Helpers
    |--------------------------------------------------------------------------
    */

    // Latest approval for a specific level
    public function latestApprovalByLevel(int $level)
    {
        return $this->approvals()
            ->where('level', $level)
            ->latest()
            ->first();
    }

    // Pending for Section Manager
    public function isPendingForSectionManager(): bool
    {
        return $this->current_level === Approval::LEVEL_SECTION_MANAGER
            && $this->status === Approval::STATUS_PENDING;
    }

    // Pending for Mechanic Officer
    public function isPendingForMechanicOfficer(): bool
    {
        return $this->current_level === Approval::LEVEL_MECHANIC_OFFICER
            && $this->status === Approval::STATUS_PENDING;
    }

    // Pending for Transport Officer
    public function isPendingForTransportOfficer(): bool
    {
        return $this->current_level === Approval::LEVEL_TRANSPORT_OFFICER
            && $this->status === Approval::STATUS_PENDING;
    }

    // Move to next phase
    public function moveToNextLevel(int $nextLevel): void
    {
        $this->update([
            'current_level' => $nextLevel,
            'status' => Approval::STATUS_PENDING,
        ]);
    }

    // Finish workflow
    public function finishWorkflow(string $finalStatus): void
    {
        $this->update([
            'current_level' => Approval::LEVEL_FINISHED,
            'status' => $finalStatus,
        ]);
    }

    public function branchName(): ?string
    {
        return $this->vehicle?->branch;
    }
}
