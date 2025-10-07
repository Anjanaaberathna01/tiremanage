<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 class Receipt extends Model
{
    protected $fillable = ['request_id', 'user_id', 'supplier_id', 'amount', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function tireRequest()
    {
        return $this->belongsTo(TireRequest::class, 'request_id');
    }


}