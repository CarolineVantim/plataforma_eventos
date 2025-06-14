<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        return Evento::all();
    }

    public function store(Request $request)
    {
        return Evento::create($request->all());
    }

    public function show($id)
    {
        return Evento::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        $evento->update($request->all());
        return $evento;
    }

    public function inscrever(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email',
        ]);

        $evento = Evento::findOrFail($id);

        if ($evento->vagas_disponiveis <= 0) {
            return response()->json(['message' => 'Não há vagas disponíveis'], 422);
        }

        if (collect($evento->inscritos)->pluck('email')->contains($request->email)) {
            return response()->json(['message' => 'Você já está inscrito neste evento'], 409);
        }

        $inscritos = $evento->inscritos ?? [];
        $inscritos[] = [
            'nome' => $request->nome,
            'email' => $request->email,
            'inscrito_em' => now(),
        ];
        $evento->inscritos = $inscritos;

        $evento->vagas_disponiveis = max(0, $evento->vagas_disponiveis - 1);

        $evento->save();

        return response()->json(['message' => 'Inscrição realizada com sucesso']);
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();
        return response()->noContent();
    }
}
