<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar el campo 'role' de tipo string con un valor predeterminado 'viewer'
            $table->string('role')->default('viewer'); // Valor predeterminado 'viewer'
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar el campo 'role' si revertimos la migración
            $table->dropColumn('role');
        });
    }
};
