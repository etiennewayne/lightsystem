<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';
    protected $primaryKey = 'device_id';

    protected $fillable = ['device_name', 'device_ip', 
        'device_token_on', 
        'device_token_off', 'ntuser'];

}
