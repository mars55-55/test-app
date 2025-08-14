<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agricultor_id')->constrained('users');
            $table->string('producto');
            $table->date('fecha_siembra');
            $table->date('fecha_cosecha')->nullable();
            $table->decimal('estimacion_ganancia', 10, 2)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('recomendaciones', function (Blueprint $table) {
            $table->id();
            $table->string('producto');
            $table->string('mes');
            $table->decimal('precio_promedio', 10, 2)->nullable();
            $table->text('motivo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracking');
        Schema::dropIfExists('recomendaciones');
    }
};