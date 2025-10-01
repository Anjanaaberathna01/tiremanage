<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

class User extends Authenticatable
{
use HasFactory, Notifiable;

protected $fillable = ['name', 'email', 'password', 'role_id', 'must_change_password'];
protected $hidden = ['password', 'remember_token'];
protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed', 'must_change_password' => 'boolean'];

public function role()
{
return $this->belongsTo(Role::class);
}
}
