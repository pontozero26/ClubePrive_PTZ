<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'modelo_id',
        'subscription_id',
        'status',
        'start_date',
        'end_date'
    ];    
}
