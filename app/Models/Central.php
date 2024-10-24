<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Central extends Model
{
    use HasFactory;

    protected $table = 'Central';  // Nombre correcto de la tabla
    public $timestamps = false;    // Si no tienes campos de timestamps

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
