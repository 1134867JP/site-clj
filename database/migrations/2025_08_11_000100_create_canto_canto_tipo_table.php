<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('canto_canto_tipo')) {
            Schema::create('canto_canto_tipo', function (Blueprint $table) {
                $table->unsignedBigInteger('canto_id');
                $table->unsignedBigInteger('canto_tipo_id');

                $table->primary(['canto_id', 'canto_tipo_id']);

                $table->foreign('canto_id')->references('id')->on('cantos')->onDelete('cascade');
                $table->foreign('canto_tipo_id')->references('id')->on('canto_tipos')->onDelete('cascade');
            });
        }

        // Backfill from cantos.canto_tipo_id when present
        if (Schema::hasColumn('cantos', 'canto_tipo_id')) {
            try {
                $rows = DB::table('cantos')->select('id', 'canto_tipo_id')->whereNotNull('canto_tipo_id')->get();
                foreach ($rows as $r) {
                    // insert ignore like behavior
                    DB::table('canto_canto_tipo')->updateOrInsert(
                        ['canto_id' => $r->id, 'canto_tipo_id' => $r->canto_tipo_id],
                        []
                    );
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Optionally drop single FK column to move fully to many-to-many
        if (Schema::hasColumn('cantos', 'canto_tipo_id')) {
            Schema::table('cantos', function (Blueprint $table) {
                try { $table->dropForeign(['canto_tipo_id']); } catch (\Throwable $e) {}
                $table->dropColumn('canto_tipo_id');
            });
        }
    }

    public function down(): void
    {
        // Recreate column canto_tipo_id (nullable) but do not backfill
        if (!Schema::hasColumn('cantos', 'canto_tipo_id')) {
            Schema::table('cantos', function (Blueprint $table) {
                $table->foreignId('canto_tipo_id')->nullable()->constrained('canto_tipos');
            });
        }

        Schema::dropIfExists('canto_canto_tipo');
    }
};
