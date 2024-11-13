<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EpisodeModel;
use Illuminate\Support\Facades\Http;

/**
 * @github.com/AlexandreOsovski
 *
 * EpisodeApi Controller
 *
 * Controller responsável por gerenciar as requisições relacionadas aos episódios da série,
 * seja na busca de todos os episódios, de um episódio específico, ou filtrado por parâmetros.
 * Também realiza interações com a API externa e o banco de dados local.
 */
class EpisodeApi extends Controller
{
    /**
     * @github.com/AlexandreOsovski
     *
     * Get All Episodes
     *
     * Recupera todos os episódios armazenados no banco de dados. Se não houver episódios
     * locais, a função faz uma requisição à API externa para recuperar todos os episódios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllEpisodes()
    {
        $episodes = EpisodeModel::all();

        if ($episodes->isEmpty()) {
            $result = Http::get(env('RICK_MORTY_API_URL') . "episode");

            if ($result->successful()) {
                return response()->json(['success' => $result->json()], 200);
            }

            return response()->json(['error' => 'Episodes Not Found'], 404);
        }

        return response()->json(['success' => $episodes], 200);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Return Episode Data
     *
     * Formata os dados do episódio para o formato de resposta da API.
     *
     * @param  \App\Models\EpisodeModel  $episode
     * @return array
     */
    private function returnEpisodeData($episode)
    {
        return [
            'id' => $episode->id,
            'name' => $episode->name,
            'air_date' => $episode->air_date,
            'episode' => $episode->episode,
            'url' => $episode->url,
            'created' => $episode->created->toIso8601String(),
        ];
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Episodes by IDs
     *
     * Recupera uma lista de episódios a partir de seus IDs. Se algum episódio não
     * estiver presente no banco de dados local, uma requisição à API externa é realizada
     * para buscá-lo. Retorna os episódios encontrados.
     *
     * @param  string  $episodeIds  IDs dos episódios separados por vírgula.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEpisodesByIds($episodeIds)
    {
        $idsArray = explode(',', $episodeIds);

        $existingEpisodes = EpisodeModel::whereIn('id', $idsArray)->get();
        $missingIds = array_diff($idsArray, $existingEpisodes->pluck('id')->toArray());

        $newEpisodes = [];

        if (!empty($missingIds)) {
            foreach ($missingIds as $id) {
                $response = Http::get(env('RICK_MORTY_API_URL') . "episode/{$id}");

                if ($response->successful()) {
                    $newEpisodes[] = $response->json();
                } else {
                    return response()->json(['error' => 'Erro ao acessar a API para buscar os episódios faltantes'], 500);
                }
            }
        }

        $allEpisodes = $existingEpisodes->toArray();
        $allEpisodes = array_merge($allEpisodes, $newEpisodes);

        return response()->json(['success' => $allEpisodes], 200);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Episodes by Filters
     *
     * Recupera episódios a partir de filtros fornecidos na requisição. Os filtros podem incluir
     * nome, data de exibição e código do episódio. Retorna os episódios que atendem a esses critérios.
     *
     * @param  \Illuminate\Http\Request  $request  Os filtros passados na requisição.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEpisodesByFilters(Request $request)
    {
        $filters = $request->only(['name', 'air_date', 'episode']);
        $query = EpisodeModel::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if ($request->has('air_date')) {
            $query->where('air_date', $filters['air_date']);
        }

        if ($request->has('episode')) {
            $query->where('episode', $filters['episode']);
        }

        $episodes = $query->get();

        if ($episodes->isEmpty()) {
            return response()->json(['error' => 'Nenhum episódio encontrado para os filtros especificados'], 404);
        }

        return response()->json(['success' => $episodes], 200);
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Single Episode
     *
     * Recupera um episódio específico pela ID. Primeiro, tenta buscar no banco de dados.
     * Se não encontrado, faz uma requisição para a API externa para obter os dados do episódio.
     *
     * @param  int  $episodeId  ID do episódio a ser recuperado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingleEpisode($episodeId)
    {
        $episode = EpisodeModel::find($episodeId);
        $episodeId = str_replace('id=', '', $episodeId);

        if (!$episode) {
            $result = Http::get(env('RICK_MORTY_API_URL') . "episode/" . $episodeId);

            if ($result->successful()) {
                return response()->json(['success' => $result->json()], 200);
            }

            return response()->json(['error' => 'Episode Not Found'], 404);
        }

        return response()->json([
            'success' => [
                $this->returnEpisodeData($episode),
            ]
        ], 200);
    }
}
