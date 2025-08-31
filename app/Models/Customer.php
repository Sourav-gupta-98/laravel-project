<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'customers';
    protected $fillable = ['unique_id', 'name', 'email', 'phone', 'password', 'logged_in_status', 'logged_in_time', 'created_at'];

    protected $hidden = ['password', 'updated_at', 'deleted_at'];
}
