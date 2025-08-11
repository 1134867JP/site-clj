<?php

namespace Database\Seeders;

use App\Models\CantoTipo;
use Illuminate\Database\Seeder;

class CantoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            ['nome' => 'Entrada', 'ord' => 10],
            ['nome' => 'Ato Penitencial', 'ord' => 20],
            ['nome' => 'Glória', 'ord' => 30],
            ['nome' => 'Aclamação', 'ord' => 40],
            ['nome' => 'Ofertório', 'ord' => 50],
            ['nome' => 'Santo', 'ord' => 60],
            ['nome' => 'Cordeiro', 'ord' => 70],
            ['nome' => 'Comunhão', 'ord' => 80],
            ['nome' => 'Ação de Graças', 'ord' => 90],
            ['nome' => 'Final', 'ord' => 100],
        ];

        foreach ($defaults as $data) {
            CantoTipo::firstOrCreate(['nome' => $data['nome']], $data);
        }
    }
}
