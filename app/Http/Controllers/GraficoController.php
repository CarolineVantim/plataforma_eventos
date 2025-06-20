<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Jenssegers\Mongodb\Facades\DB;


class GraficoController extends Controller
{
    public function eventosPorLocal()
    {
        $consulta = Evento::raw(function($collection) {
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
        }, iterator_to_array($consulta)));
    }

    public function eventosPorTema()
    {
        $consulta = Evento::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$tema',
                        'quantidade' => ['$sum' => 1]
                    ]
                ],
                [ '$sort' => ['quantidade' => -1] ],
                [ '$limit' => 5 ]
            ]);
        });

        return response()->json(array_map(function($item) {
            return [
                'tema' => $item['_id'],
                'quantidade' => $item['quantidade']
            ];
        }, iterator_to_array($consulta)));
    }

    public function eventosPorMes()
    {
        $consulta = Evento::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => [
                            'ano' => ['$year' => '$data_evento'],
                            'mes' => ['$month' => '$data_evento']
                        ],
                        'quantidade' => ['$sum' => 1]
                    ]
                ],
                [ '$sort' => ['_id.ano' => 1, '_id.mes' => 1] ],
                [ '$limit' => 5 ]
            ]);
        });

        return response()->json(array_map(function($item) {
            return [
                'mes' => sprintf('%02d/%d', $item['_id']['mes'], $item['_id']['ano']),
                'quantidade' => $item['quantidade']
            ];
        }, iterator_to_array($consulta)));
    }

    public function eventosPorPromotor()
    {
        $consulta = Evento::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$promotor',
                        'quantidade' => ['$sum' => 1]
                    ]
                ],
                [ '$sort' => ['quantidade' => -1] ],
                [ '$limit' => 5 ]
            ]);
        });
        return response()->json(array_map(function($item) {
            return [
                'promotor' => $item['_id'],
                'quantidade' => $item['quantidade']
            ];
        }, iterator_to_array($consulta)));
    }

    public function eventosComVagas()
    {
        $consulta = Evento::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'vagas_disponiveis' => ['$gt' => 0]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$tema',
                        'total_vagas_disponiveis' => ['$sum' => '$vagas_disponiveis']
                    ]
                ],
                [
                    '$sort' => ['total_vagas_disponiveis' => -1]
                ],
                [
                    '$limit' => 5
                ]
            ]);
        });

        return response()->json(array_map(function($item) {
            return [
                'tema' => $item['_id'],
                'total_vagas_disponiveis' => $item['total_vagas_disponiveis']
            ];
        }, iterator_to_array($consulta)));
    }
}
