<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection'); // Tipo de conexión (ej: redis, database, etc.)
            $table->text('queue'); // Nombre de la cola
            $table->longText('payload'); // Datos del job
            $table->longText('exception'); // Mensaje de error
            $table->timestamp('failed_at')->useCurrent(); // Fecha de fallo
        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
};
