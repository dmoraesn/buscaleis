<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMateria extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relacionamento: Um TipoMateria TEM MUITAS Materias
    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'tipo_id');
    }
}
