<?php

namespace App\Models; use Illuminate\Database\Eloquent\Factories\HasFactory; use
    Illuminate\Database\Eloquent\Model; class Receipt extends Model {
        use HasFactory; protected $fillable=[
    'request_id', 'supplier_id' ,
     'description' , 'amount' ,
    ];

    public function request() { return $this->
belongsTo(TireRequest::class, 'request_id');
}

public function supplier()
{
return $this->belongsTo(Supplier::class, 'supplier_id');
}
}
