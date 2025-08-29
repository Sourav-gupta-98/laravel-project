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

    protected $fillable = ['unique_id', 'order_id', 'product_id', 'quantity', 'price', 'total', 'created_at'];

    protected $hidden = ['updated_at', 'updated_at'];
}
