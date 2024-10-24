<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $table = 'Registro';  // Asegúrate de usar el nombre correcto de la tabla
    public $timestamps = false;     // Si no tienes campos de timestamps
    
    protected $fillable = [
        'employeeID', 
        'authDateTime', 
        'authDate', 
        'authTime', 
        'direction', 
        'deviceName', 
        'deviceSN', 
        'personName', 
        'cardNo'
    ];

}
