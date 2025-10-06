<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relacionamento: Um Autor PODE TER VÁRIAS Matérias
    public function materias(): BelongsToMany
    {
        return $this->belongsToMany(Materia::class, 'autor_materia');
    }
}
