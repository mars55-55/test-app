<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Actualiza los campos rellenables
    protected $fillable = ['name', 'email', 'password', 'role_id'];

    protected $hidden = ['password'];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'agricultor_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }
}
