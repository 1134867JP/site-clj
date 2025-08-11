<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CantoTipo extends Model
{
    use HasFactory;

    protected $table = 'canto_tipos';

    protected $fillable = [
        'nome',
        'ord',
    ];
}
