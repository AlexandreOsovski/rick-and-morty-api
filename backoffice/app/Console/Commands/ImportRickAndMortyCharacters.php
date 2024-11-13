<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\CharacterModel;
use App\Models\LocationModel;

class ImportRickAndMortyCharacters extends Command
{
    /**
     *@github.com/AlexandreOsovski
     */
    protected $signature = 'import:rickandmorty {--limit= : Quantidade de personagens a serem importados}';
    protected $description = 'Importa todos os personagens da API do Rick and Morty e os salva no banco de dados';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Iniciando a importação dos personagens...');

        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $data = Http::get(env('RICK_MORTY_API_URL') . 'character');
        $totalCharacters = $data->json()['info']['count'];

        if ($limit && $limit > $totalCharacters) {
            $this->info("O número de items máximo é {$totalCharacters} personagens...");
            die;
        }

        if ($limit && $limit <= $totalCharacters) {
            $this->info("Importando todos os {$totalCharacters} personagens...");
            $totalCharacters = $limit;
        }

        for ($id = 1; $id <= $totalCharacters; $id++) {
            $url = env('RICK_MORTY_API_URL') . "character/{$id}";

            $response = Http::get($url);

            if ($response->successful()) {
                $characterData = $response->json();

                if (!CharacterModel::where('character_api_id', $characterData['id'])->exists()) {
                    $origin = LocationModel::firstOrCreate(
                        ['url' => $characterData['origin']['url']],
                        ['name' => $characterData['origin']['name']]
                    );

                    $location = LocationModel::firstOrCreate(
                        ['url' => $characterData['location']['url']],
                        ['name' => $characterData['location']['name']]
                    );

                    CharacterModel::create([
                        'character_api_id' => $characterData['id'],
                        'name' => $characterData['name'],
                        'status' => $characterData['status'],
                        'species' => $characterData['species'],
                        'type' => $characterData['type'],
                        'gender' => $characterData['gender'],
                        'origin_id' => $origin->id,
                        'location_id' => $location->id,
                        'image' => $characterData['image'],
                        'episode' => $characterData['episode'],
                        'url' => $characterData['url'],
                        'created' => $characterData['created'],
                    ]);

                    $this->info("Personagem '{$characterData['name']}' cadastrado com sucesso.");
                } else {
                    $this->info("Personagem '{$characterData['name']}' já está cadastrado.");
                }
            } else {
                $this->error("Erro ao acessar o personagem com ID {$id}.");
            }
        }

        $this->info('Importação concluída com sucesso!');
    }
}