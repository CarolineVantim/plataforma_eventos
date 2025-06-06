<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoControllerTest extends TestCase
{
    /**
     * A basic unit test.
     *
     * @return void
     */
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function testCreateEvent(): array
    {
        return [
            'tema' => 'Evento de Teste',
            'descricao_evento' => 'Descrição do evento',
            'data_evento' => '2025-06-30T19:00:00Z',
            'vagas_totais' => 100,
            'vagas_disponiveis' => 100
        ];
    }

    private function testCreateEventMock(array $testCreateEvent): array
    {
        $eventoMock = Mockery::mock('alias:' . Evento::class);
        $eventoMock->shouldReceive('create')
            ->once()
            ->with($testCreateEvent)
            ->andReturn((object) $testCreateEvent);
    }

    public function testStoreEvent(): array
    {
        $dados = $this->testCreateEvent();

        $this->testCreateEventMock($dados);

        $eventController = new EventoController();
        $request = Request::create('/fake-url', 'POST', $dados);
        $response = $eventController->store($request);

        $this->assertEquals((object) $dados, $response); 
    }
}
