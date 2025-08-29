<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Orders extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'orders';

    protected $fillable = ['unique_id', 'order_number', 'customer_id', 'status', 'payment_method', 'payment_status', 'transaction_id', 'shipping_address', 'billing_address', 'created_at', 'updated_at'];


    protected $hidden = ['deleted_at'];
}
