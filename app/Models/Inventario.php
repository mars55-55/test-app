<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = ['producto_id', 'cantidad', 'alerta_stock_bajo'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
