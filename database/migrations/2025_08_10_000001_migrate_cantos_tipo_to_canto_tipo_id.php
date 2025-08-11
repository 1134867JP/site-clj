<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateCantosTipoToCantoTipoId extends Migration
{
    public function up(): void
    {
        Schema::table('cantos', function (Blueprint $table) {
            // Adiciona a nova FK (inicialmente nullable para permitir backfill)
            if (!Schema::hasColumn('cantos', 'canto_tipo_id')) {
                $table->foreignId('canto_tipo_id')->nullable()->constrained('canto_tipos');
            }
        });

        // Backfill: mapear cantos.tipo -> canto_tipos.id
        if (Schema::hasTable('canto_tipos')) {
            // Carrega todos os tipos existentes
            $tipos = DB::table('canto_tipos')->get()->keyBy('nome');

            // Descobre todos os valores distintos existentes em cantos.tipo
            $valores = DB::table('cantos')->select('tipo')->distinct()->pluck('tipo');

            foreach ($valores as $nome) {
                if ($nome === null) continue;
                if (!isset($tipos[$nome])) {
                    // Cria registro faltante com uma ordem alta
                    $id = DB::table('canto_tipos')->insertGetId([
                        'nome' => $nome,
                        'ord'  => 999,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $tipos[$nome] = (object) ['id' => $id, 'nome' => $nome, 'ord' => 999];
                }
                // Atualiza todos os cantos com esse nome para apontarem para o id
                DB::table('cantos')->where('tipo', $nome)->update(['canto_tipo_id' => $tipos[$nome]->id]);
            }
        }

        // Remover a coluna enum antiga
        if (Schema::hasColumn('cantos', 'tipo')) {
            Schema::table('cantos', function (Blueprint $table) {
                $table->dropColumn('tipo');
            });
        }
    }

    public function down(): void
    {
        // Recria a coluna antiga e tenta preencher de volta a partir do relacionamento
        Schema::table('cantos', function (Blueprint $table) {
            if (!Schema::hasColumn('cantos', 'tipo')) {
                // Recria como string simples para maior compatibilidade de SGBD
                $table->string('tipo')->nullable();
            }
        });

        // Backfill reverso: copia nome do tipo relacionado
        if (Schema::hasColumn('cantos', 'canto_tipo_id') && Schema::hasColumn('cantos', 'tipo')) {
            $rows = DB::table('cantos')->select('id', 'canto_tipo_id')->get();
            $map  = DB::table('canto_tipos')->pluck('nome', 'id');
            foreach ($rows as $r) {
                $nome = $map[$r->canto_tipo_id] ?? null;
                DB::table('cantos')->where('id', $r->id)->update(['tipo' => $nome]);
            }
        }

        // Remove a FK/coluna nova
        Schema::table('cantos', function (Blueprint $table) {
            if (Schema::hasColumn('cantos', 'canto_tipo_id')) {
                try { $table->dropForeign(['canto_tipo_id']); } catch (\Throwable $e) {}
                $table->dropColumn('canto_tipo_id');
            }
        });
    }
}
