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

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_producto')
                    ->withPivot('cantidad') // Incluye la cantidad en la tabla pivote
                    ->withTimestamps(); // Registra las marcas de tiempo
    }
}
