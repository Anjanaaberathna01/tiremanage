<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TireRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'tire_id',
        'damage_description',
        'tire_images',
        'status',
    ];

    protected $casts = [
        'tire_images' => 'array',
    ];

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
}
