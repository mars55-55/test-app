<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'cantidad_disponible', 'agricultor_id'];

    public function agricultor()
    {
        return $this->belongsTo(User::class, 'agricultor_id');
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }
}
