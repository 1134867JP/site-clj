<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canto extends Model
{
    protected $fillable = ['titulo', 'letra', 'notas', 'tom'];

    public function tipos()
    {
        return $this->belongsToMany(CantoTipo::class, 'canto_canto_tipo', 'canto_id', 'canto_tipo_id');
    }
}
