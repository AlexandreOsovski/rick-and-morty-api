<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CharacterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @github.com/AlexandreOsovski
 *
 * CharacterApi Controller
 *
 * Controller responsável por gerenciar as requisições relacionadas aos personagens,
 * seja na busca de todos os personagens, de um personagem específico ou filtrado.
 * Além disso, gerencia a interação com a API externa e o banco de dados local.
 */
class CharacterApi extends Controller
{
    /**
     * @github.com/AlexandreOsovski
     *
     * Get All Characters
     *
     * Recupera todos os personagens da API externa e retorna um array com os dados
     * de cada um. Caso a consulta seja bem-sucedida, retorna os dados. Caso contrário,
     * retorna um erro indicando que os personagens não foram encontrados.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCharacters()
    {
        $result = Http::get(env('RICK_MORTY_API_URL') . "character");

        if ($result->successful()) {
            return response()->json(['status' => 'success', 'data' => $result->json()['results']], 200);
        }

        return response()->json(['status' => 'error', 'data' => 'Character Not Found'], 404);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Single Character
     *
     * Recupera um personagem específico pela ID. Primeiro, tenta buscar no banco de dados
     * local. Se o personagem não for encontrado localmente, faz uma requisição para a
     * API externa. Retorna os dados do personagem encontrado ou um erro caso não encontrado.
     *
     * @param  int  $character_id  ID do personagem a ser recuperado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingleCharacter($character_id)
    {
        $character = CharacterModel::find($character_id);
        $character_id = str_replace('id=', '', $character_id);

        if (!$character) {
            $result = Http::get(env('RICK_MORTY_API_URL') . "character/" . $character_id);

            if ($result->successful()) {
                return response()->json(['status' => 'success', 'data' => $result->json()], 200);
            } else {
                return response()->json(['status' => 'error', 'data' => 'Character Not Found'], 404);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [$this->returnData($character)]
        ], 200);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Return Data
     *
     * Formata os dados do personagem para o formato de resposta da API.
     *
     * @param  \App\Models\CharacterModel  $character
     * @return array
     */
    private function returnData($character)
    {
        return [
            'id' => $character->character_api_id,
            'name' => $character->name,
            'status' => $character->status,
            'species' => $character->species,
            'type' => $character->type,
            'gender' => $character->gender,
            'origin' => [
                'name' => $character->origin->name,
                'url' => $character->origin->url,
            ],
            'location' => [
                'name' => $character->location->name,
                'url' => $character->location->url,
            ],
            'image' => $character->image,
            'episode' => $character->episode,
            'url' => $character->url,
            'created' => $character->created->toIso8601String(),
        ];
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Characters by IDs
     *
     * Recupera uma lista de personagens através de seus IDs. Se algum personagem não
     * existir no banco de dados, uma requisição à API externa é realizada para recuperá-lo.
     *
     * @param  string  $ids  IDs dos personagens separados por vírgula.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharactersByIds($ids)
    {
        $idsArray = explode(',', $ids);

        $existingCharacters = CharacterModel::whereIn('character_api_id', $idsArray)->with(['origin', 'location'])->get();
        $missingIds = array_diff($idsArray, $existingCharacters->pluck('character_api_id')->toArray());

        $newCharacters = [];

        if (!empty($missingIds)) {
            $missingIdsStr = implode(',', $missingIds);
            $response = Http::get(env('RICK_MORTY_API_URL') . "character/{$missingIdsStr}");

            if ($response->successful()) {
                $newCharacters = $response->json();
            } else {
                return response()->json(['status' => 'error', 'data' => 'Erro ao acessar a API para buscar os personagens faltantes'], 500);
            }
        }

        $allCharacters = $existingCharacters->toArray();
        $allCharacters = array_merge($allCharacters, $newCharacters);

        return response()->json(['status' => 'success', 'data' => $allCharacters], 200);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Characters by Filters
     *
     * Recupera personagens a partir de filtros fornecidos na requisição. Os filtros podem
     * incluir nome, status, espécie, tipo, gênero e quantidade por página. Os resultados
     * são paginados.
     *
     * @param  \Illuminate\Http\Request  $request  Os filtros passados na requisição.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharactersByFilters(Request $request)
    {
        $filters = $request->only(['name', 'status', 'species', 'type', 'gender', 'qtyPage']);

        $query = CharacterModel::query()->with(['location']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['species'])) {
            $query->where('species', $filters['species']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        $qty = !empty($filters['qtyPage']) ? $filters['qtyPage'] : 20;
        $perPage = $request->get('per_page', $qty);

        $characters = $query->paginate($perPage);

        if ($characters->isEmpty()) {
            return response()->json(['status' => 'error', 'data' => 'Nenhum personagem encontrado para os filtros especificados'], 404);
        }

        return response()->json($characters, 200);
    }
}
