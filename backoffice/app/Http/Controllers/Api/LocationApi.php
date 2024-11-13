<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @github.com/AlexandreOsovski
 *
 * LocationApi Controller
 *
 * Controller responsável por gerenciar as requisições relacionadas às localizações,
 * seja para buscar todas as localizações, uma localização específica, múltiplas localizações
 * ou aplicar filtros para uma busca mais refinada.
 */
class LocationApi extends Controller
{
    /**
     * @github.com/AlexandreOsovski
     *
     * Get Locations
     *
     * Recupera todas as localizações disponíveis na API externa. Caso a requisição
     * seja bem-sucedida, os dados são retornados, caso contrário, um erro é retornado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocations()
    {
        $response = Http::get(env('RICK_MORTY_API_URL') . 'location');

        if ($response->successful()) {
            return response()->json(['success' => $response->json()], 200);
        } else {
            return response()->json(['error' => 'Erro ao acessar a API de locações'], 500);
        }
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Single Location
     *
     * Recupera uma localização específica pela ID. Se a requisição for bem-sucedida,
     * os dados da localização são retornados. Caso contrário, retorna um erro.
     *
     * @param  int  $locationId  ID da localização a ser recuperada.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingleLocation($locationId)
    {
        $locationId = str_replace('id=', '', $locationId);
        $response = Http::get(env('RICK_MORTY_API_URL') . 'location/', $locationId);

        if ($response->successful()) {
            return response()->json(['success' => $response->json()], 200);
        } else {
            return response()->json(['error' => 'Erro ao acessar a API de locações'], 500);
        }
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Multiple Locations
     *
     * Recupera várias localizações com base nos IDs fornecidos. Os IDs são passados como
     * uma string separada por vírgulas, e a função realiza uma requisição para a API externa
     * para buscar os dados das localizações correspondentes.
     *
     * @param  string  $locationIds  IDs das localizações separados por vírgula.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMultiplesLocations($locationIds)
    {
        $idsArray = explode(',', $locationIds);

        $idsStr = implode(',', $idsArray);
        $response = Http::get(env('RICK_MORTY_API_URL') . "location/{$idsStr}");

        if ($response->successful()) {
            $locationData = $response->json();
            return response()->json(['success' => $locationData], 200);
        } else {
            return response()->json(['error' => 'Erro ao acessar a API para buscar as locações faltantes'], 500);
        }
    }

    /**
     * @github.com/AlexandreOsovski
     *
     * Get Locations by Filters
     *
     * Recupera as localizações aplicando filtros como nome, tipo e dimensão. A função
     * verifica os parâmetros da requisição e constrói a URL com os filtros necessários
     * para a consulta à API externa.
     *
     * @param  \Illuminate\Http\Request  $request  Os filtros passados na requisição.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiltersLocations(Request $request)
    {
        $url = env('RICK_MORTY_API_URL') . 'location';

        $queryParams = [];

        if ($request->has('name')) {
            $queryParams['name'] = $request->input('name');
        }

        if ($request->has('type')) {
            $queryParams['type'] = $request->input('type');
        }

        if ($request->has('dimension')) {
            $queryParams['dimension'] = $request->input('dimension');
        }

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $response = Http::get($url);

        if ($response->successful()) {
            $locationData = $response->json();
            return response()->json(['success' => $locationData], 200);
        } else {
            return response()->json(['error' => 'Erro ao acessar a API para buscar as locações'], 500);
        }
    }
}