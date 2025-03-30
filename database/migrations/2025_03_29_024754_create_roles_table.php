<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ejemplo: 'admin', 'agricultor', 'comprador'
            $table->timestamps();
        });

        // Agregar la columna `role_id` a la tabla `users`
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('password');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->dropColumn('role'); // Elimina la columna `role` si ya no es necesaria
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->enum('role', ['agricultor', 'comprador', 'admin'])->default('comprador'); // Restaura la columna `role` si es necesario
        });

        Schema::dropIfExists('roles');
    }
};
