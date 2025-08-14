<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $table = 'tracking';

    protected $fillable = [
        'agricultor_id',
        'producto',
        'fecha_siembra',
        'fecha_cosecha',
        'estimacion_ganancia',
        'notas',
    ];

    // Relación con el agricultor (usuario)
    public function agricultor()
    {
        return $this->belongsTo(User::class, 'agricultor_id');
    }
}