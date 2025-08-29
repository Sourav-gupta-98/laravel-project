<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class products extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['unique_id', 'name', 'price', 'stock', 'description', 'image', 'category', 'added_by', 'created_at'];

    protected $hidden = ['updated_at', 'deleted_at'];

    public function added_by()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

}
