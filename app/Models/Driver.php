<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'full_name', 'mobile', 'id_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
