<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cantos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('letra');
            $table->string('notas')->nullable();
            $table->string('tom')->nullable();
            $table->enum('tipo', [
                'Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo',
                'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cantos');
    }
};
