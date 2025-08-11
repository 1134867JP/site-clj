<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canto extends Model
{
    protected $fillable = ['titulo', 'letra', 'notas', 'tom', 'canto_tipo_id'];

    public function tipo()
    {
        return $this->belongsTo(CantoTipo::class, 'canto_tipo_id');
    }
}
