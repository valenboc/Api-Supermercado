<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supermercado extends Model{
    use HasFactory;
    protected $table = 'supermercados';
    protected $primaryKey = 'ID_supermercado'; 
    protected $fillable = ['Nombre', 'NIT', 'Direccion', 'Logo', 'Longitud', 'Latitud', 'ID_ciudad'];

    protected $hidden = ['created_at', 'updated_at'];
    
    public function ciudad(){
        return $this->belongsTo(Ciudades::class, 'ID_ciudad');
    }
}
