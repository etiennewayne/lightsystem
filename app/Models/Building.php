<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = 'buildings';
    protected $primaryKey = 'building_id';

    protected $fillable = ['building_name'];

    public function devices(){
        return $this->hasMany(Device::class, 'building_id', 'building_id');
    }


}