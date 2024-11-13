<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\CharacterModel;

/**
 * Testes para a API de personagens.
 */
class CharacterApiTest extends TestCase
{
    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se o método 'getAllCharacters' retorna todos os personagens corretamente.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_all_characters()
    {
        Http::fake([
            env('RICK_MORTY_API_URL') . 'character' => Http::response([
                'results' => [
                    [
                        'id' => 1,
                        'name' => 'Rick Sanchez',
                        'status' => 'Alive',
                        'species' => 'Human',
                        'gender' => 'Male',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Morty Smith',
                        'status' => 'Alive',
                        'species' => 'Human',
                        'gender' => 'Male',
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/characters');

        $response->assertStatus(200);
        $response->assertJson(json_decode($response->getContent(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se o método 'getAllCharacters' retorna erro quando a API externa falha.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_404_if_characters_not_found_from_api()
    {
        Http::fake([
            env('RICK_MORTY_API_URL') . 'character' => Http::response([], 404)
        ]);

        $response = $this->getJson('/api/characters');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Character Not Found']);
    }


    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se o método 'getSingleCharacter' busca o personagem na API externa quando não encontrado no banco.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_single_character_from_api_if_not_found_in_database()
    {
        $character_id = 999;

        Http::fake([
            env('RICK_MORTY_API_URL') . "character/{$character_id}" => Http::response([
                'id' => $character_id,
                'name' => 'Rick Sanchez',
                'status' => 'Alive',
                'species' => 'Human',
                'gender' => 'Male',
            ], 200)
        ]);

        $response = $this->getJson('/api/characters/' . $character_id);

        $response->assertStatus(200);
        $response->assertJson(json_decode($response->content(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se o método 'getSingleCharacter' retorna erro quando o personagem não é encontrado.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_404_if_character_not_found()
    {
        Http::fake([
            env('RICK_MORTY_API_URL') . "character/999" => Http::response([], 404)
        ]);

        $response = $this->getJson('/api/characters/999');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Character Not Found']);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se o método 'getCharactersByIds' retorna personagens com base nos IDs fornecidos.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_characters_by_ids()
    {

        $ids = '1,2';

        $response = $this->getJson('/api/characters/multiple/' . $ids);

        $response->assertStatus(200);
        $response->assertJson(json_decode($response->content(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se o método 'getCharactersByIds' busca personagens ausentes na base de dados via API externa.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_characters_by_ids_from_api_if_not_found_in_database()
    {
        $ids = '999,1000';

        Http::fake([
            env('RICK_MORTY_API_URL') . "character/999,1000" => Http::response([
                [
                    'id' => 999,
                    'name' => 'Rick Sanchez',
                    'status' => 'Alive',
                ],
                [
                    'id' => 1000,
                    'name' => 'Morty Smith',
                    'status' => 'Alive',
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/characters/multiple/' . $ids);

        $response->assertStatus(200);
        $response->assertJson(json_decode($response->content(), true));
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Testa se o método 'getCharactersByIds' retorna erro quando ocorre um erro ao acessar a API externa.
     *
     * @return void
     */
    /** @test */
    public function it_should_return_500_if_error_occurs_when_fetching_from_api()
    {
        $ids = '999,1000';

        Http::fake([
            env('RICK_MORTY_API_URL') . "character/999,1000" => Http::response([], 500)
        ]);

        $response = $this->getJson('/api/characters/multiple/' . $ids);

        $response->assertStatus(500);
        $response->assertJson(['error' => 'Erro ao acessar a API para buscar os personagens faltantes']);
    }
}
