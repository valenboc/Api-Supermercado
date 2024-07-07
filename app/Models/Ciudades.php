<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudades extends Model{
    use HasFactory;

    protected $table = 'ciudades';
    protected $primaryKey = 'ID_ciudad'; 
    protected $fillable = ['Nombre'];

    protected $hidden = ['created_at', 'updated_at'];

    public function superMercados(){
        return $this->hasMany(Supermercado::class, 'ID_ciudad');
    }
}
