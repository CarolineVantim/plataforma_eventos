<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Jenssegers\Mongodb\Facades\DB;


class GraficoController extends Controller
{
    public function eventosPorLocal()
    {
        $dados = Evento::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$localizacao',
                        'quantidade' => ['$sum' => 1]
                    ]
                ],
                [ '$sort' => ['quantidade' => -1] ],
                [ '$limit' => 5 ]
            ]);
        });

        return response()->json(array_map(function($item) {
            return [
                'localizacao' => $item['_id'],
                'quantidade' => $item['quantidade']
            ];
        }, iterator_to_array($dados)));
    }

    public function eventosPorTema()
    {
        return Evento::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$tema', 'total' => ['$sum' => 1]]],
                ['$sort' => ['total' => -1]]
            ]);
        });
    }

    public function eventosPorMes()
    {
        return Evento::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => [
                            'ano' => ['$year' => ['$dateFromString' => ['dateString' => '$data_evento']]],
                            'mes' => ['$month' => ['$dateFromString' => ['dateString' => '$data_evento']]]
                        ],
                        'total' => ['$sum' => 1]
                    ]
                ],
                ['$sort' => ['_id.ano' => 1, '_id.mes' => 1]]
            ]);
        });
    }

    public function eventosPorPromotor()
    {
        return Evento::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$promotor', 'total' => ['$sum' => 1]]],
                ['$sort' => ['total' => -1]]
            ]);
        });
    }

    public function eventosComVagas()
    {
        return Evento::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$project' => [
                        'tema' => 1,
                        'vagas_disponiveis' => 1,
                        'vagas_totais' => 1,
                        'inscritos_count' => ['$size' => ['$ifNull' => ['$inscritos', []]]],
                    ]
                ],
                [
                    '$match' => [
                        '$expr' => [
                            '$gt' => ['$vagas_disponiveis', 0]
                        ]
                    ]
                ]
            ]);
        });
    }
}
