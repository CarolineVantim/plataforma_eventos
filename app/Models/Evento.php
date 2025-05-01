<?php

namespace App\Models;

use Mongodb\Laravel\Eloquent\Model;

class Evento extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'eventos';

    protected $fillable = [
        'tema',
        'descricao_evento',
        'data_evento',
        'inscritos',
        'promotor',
        'localizacao',
        'tags',
        'vagas_totais',
        'vagas_disponiveis'
    ];

    protected $casts = [
        'data_evento' => 'datetime',
        'inscritos' => 'array',
        'promotor' => 'array',
        'localizacao' => 'array',
        'tags' => 'array',
    ];
}
