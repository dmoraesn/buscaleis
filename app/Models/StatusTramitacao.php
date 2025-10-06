<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTramitacao extends Model
{
    use HasFactory;

    /**
     * Desabilita a proteção contra atribuição em massa.
     *
     * @var array
     */
    protected $guarded = [];
}
