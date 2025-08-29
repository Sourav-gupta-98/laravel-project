<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Cart extends Model
{
    use softDeletes, Notifiable, HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $fillable = ['unique_id', 'customer_id', 'product_id', 'quantity', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id')->with(['added_by']);
    }

}
