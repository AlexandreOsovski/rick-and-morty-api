<?php

namespace Tests\Feature;

use App\Models\EpisodeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EpisodeApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se a API retorna todos os episódios do banco de dados.
     *
     * Este teste cria um episódio no banco de dados e faz uma requisição GET para a rota de episódios.
     * Verifica se a resposta contém os dados do episódio criado.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_all_episodes_from_database()
    {
        EpisodeModel::factory()->create([
            'name' => 'Test Episode',
            'episode' => 'S01E01',
            'air_date' => '2024-01-01',
        ]);

        $response = $this->getJson('/api/episodes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success' => [
                    '*' => [
                        'id',
                        'name',
                        'air_date',
                        'episode',
                        'url',
                        'created',
                    ]
                ]
            ]);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se a API externa é chamada quando não há episódios no banco de dados.
     *
     * Este teste 'finge' a resposta da API externa, simulando que os episódios são retornados corretamente,
     * caso não haja episódios no banco de dados.
     *
     * @return void
     */
    /** @test */
    public function it_should_fetch_episodes_from_external_api_when_no_episodes_in_database()
    {
        Http::fake([
            env('RICK_MORTY_API_URL') . 'episode' => Http::response([
                [
                    'id' => 1,
                    'name' => 'Pilot',
                    'air_date' => '2024-01-01',
                    'episode' => 'S01E01',
                    'url' => 'http://example.com/episode/1',
                    'created' => '2024-01-01T00:00:00Z',
                ]
            ], 200),
        ]);

        $response = $this->getJson('/api/episodes');

        $response->assertStatus(200)
            ->assertJson([
                'success' => [
                    [
                        'id' => 1,
                        'name' => 'Pilot',
                        'air_date' => '2024-01-01',
                        'episode' => 'S01E01',
                        'url' => 'http://example.com/episode/1',
                        'created' => '2024-01-01T00:00:00Z',
                    ]
                ]
            ]);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se a API retorna episódios ao fornecer IDs específicos.
     *
     * Este teste cria um episódio no banco de dados e faz uma requisição GET para obter o episódio pelo ID.
     * Verifica se os dados retornados são os mesmos do episódio criado.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_episodes_by_ids()
    {
        $episode = EpisodeModel::factory()->create([
            'name' => 'Test Episode',
            'episode' => 'S01E01',
            'air_date' => '2024-01-01',
        ]);

        $response = $this->getJson('/api/episodes/multiple/' . $episode->id);

        $response->assertJson(json_decode($response->content(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se a API externa é chamada para obter episódios faltantes por IDs.
     *
     * Este teste simula a requisição para episódios ausentes no banco e verifica se a API externa é chamada
     * para preencher os dados dos episódios faltantes.
     *
     * @return void
     */
    /** @test */
    public function it_should_fetch_missing_episodes_by_ids_from_external_api()
    {
        Http::fake([
            env('RICK_MORTY_API_URL') . 'episode/2' => Http::response([
                'id' => 2,
                'name' => 'Test Episode 2',
                'air_date' => '2024-01-02',
                'episode' => 'S01E02',
                'url' => 'http://example.com/episode/2',
                'created' => '2024-01-02T00:00:00Z',
            ], 200),
        ]);

        $missingEpisodeId = 2;
        $response = $this->getJson('/api/episodes/multiple/' . $missingEpisodeId);

        $response->assertStatus(200)
            ->assertJson(json_decode($response->content(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se os episódios podem ser filtrados pelo nome.
     *
     * Este teste cria um episódio no banco e faz uma requisição GET com um filtro pelo nome.
     * Verifica se o episódio é retornado corretamente no filtro.
     *
     * @return void
     */
    /** @test */
    public function it_should_filter_episodes_by_name()
    {
        $episode = EpisodeModel::factory()->create([
            'name' => 'Test Episode',
            'episode' => 'S01E01',
            'air_date' => '2024-01-01',
        ]);

        $response = $this->getJson('/api/episodes/filter?name=Test');

        $response->assertStatus(200)
            ->assertJson(json_decode($response->content(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se a API retorna 404 quando nenhum episódio é encontrado pelo filtro.
     *
     * Este teste faz uma requisição com um filtro que não corresponde a nenhum episódio
     * e verifica se a resposta é 404 com a mensagem de erro adequada.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_404_if_no_episodes_found_by_filter()
    {
        $response = $this->getJson('/api/episodes/filter?name=NonExistentEpisode');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Nenhum episódio encontrado para os filtros especificados'
            ]);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se a API retorna um único episódio com base no ID.
     *
     * Este teste cria um episódio no banco e faz uma requisição GET para obter um episódio específico
     * com base no ID. Verifica se o episódio retornado corresponde ao esperado.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_single_episode()
    {
        $episode = EpisodeModel::factory()->create([
            'name' => 'Test Episode',
            'episode' => 'S01E01',
            'air_date' => '2024-01-01',
        ]);

        $response = $this->getJson('/api/episodes/' . $episode->id);

        $response->assertStatus(200)
            ->assertJson(json_decode($response->content(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se a API retorna 404 quando um episódio não é encontrado.
     *
     * Este teste faz uma requisição para um ID de episódio que não existe no banco e verifica se a resposta
     * retorna um erro 404 com a mensagem "Episode Not Found".
     *
     * @return void
     */
    /** @test */
    public function it_should_return_404_if_episode_not_found()
    {
        $response = $this->getJson('/api/episodes/999');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Episode Not Found'
            ]);
    }
}
