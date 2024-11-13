<?php
//@github.com/AlexandreOsovski

use App\Http\Controllers\Api\{
    CharacterApi,
    EpisodeApi,
    LocationApi,
};

use Illuminate\{
    Support\Facades\Route,
    Http\Request,
};


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('characters')->group(function () {
    Route::get('/', [CharacterApi::class, 'getAllCharacters']);
    Route::get('/id={character_id}', [CharacterApi::class, 'getSingleCharacter']);
    Route::get('/multiple/{ids}', [CharacterApi::class, 'getCharactersByIds']);
    Route::get('/filter', [CharacterApi::class, 'getCharactersByFilters']);
});

Route::prefix('locations')->group(function () {
    Route::get('/', [LocationApi::class, 'getLocations']);
    Route::get('/id={locationId}', [LocationApi::class, 'getSingleLocation']);
    Route::get('/multiple/{locationIds}', [LocationApi::class, 'getMultiplesLocations']);
    Route::get('/filter', [LocationApi::class, 'getFiltersLocations']);
});

Route::prefix('episodes')->group(function () {
    Route::get('/', [EpisodeApi::class, 'getAllEpisodes']);
    Route::get('/id={episodeId}', [EpisodeApi::class, 'getSingleEpisode']);
    Route::get('/multiple/{episodeId}', [EpisodeApi::class, 'getEpisodesByIds']);
    Route::get("/filter", [EpisodeApi::class, 'getEpisodesByFilters']);
});