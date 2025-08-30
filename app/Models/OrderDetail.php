<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrderDetail extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'order_details';

    protected $fillable = ['unique_id', 'order_id', 'product_id', 'product_added_by', 'quantity', 'price', 'total', 'created_at'];

    protected $hidden = ['updated_at', 'deleted_at'];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id')->with(['customer']);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'product_added_by', 'id');
    }
}
