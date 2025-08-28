<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'customers';
    protected $fillable = ['unique_id', 'name', 'email', 'phone', 'password', 'created_at'];

    protected $hidden = ['password', 'updated_at', 'deleted_at'];
}
