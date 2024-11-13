<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\EpisodeModel;

class FetchEpisodes extends Command
{
    /**
     * @github.com/AlexandreOsovski
     */
    protected $signature = 'fetch:episodes {--limit= : Limite de episódios para importar}';
    protected $description = 'Busca e salva os episódios do Rick and Morty, sem duplicar, e associa os personagens aos episódios';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Iniciando a importação dos personagens...');

        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $data = Http::get(env('RICK_MORTY_API_URL') . 'episode');
        $totalCharacters = $data->json()['info']['count'];

        if ($limit && $limit > $totalCharacters) {
            $this->info("O número de episódios máximo é {$totalCharacters}...");
            die;
        }

        if ($limit && $limit <= $totalCharacters) {
            $this->info("Importando todos os {$totalCharacters} episódios...");
            $totalCharacters = $limit;
        }

        for ($id = 1; $id <= $totalCharacters; $id++) {
            $url = env('RICK_MORTY_API_URL') . "episode/{$id}";

            $response = Http::get($url);

            if ($response->successful()) {
                $characterData = $response->json();

                if (!EpisodeModel::where('episode', $characterData['episode'])->exists()) {

                    EpisodeModel::create([
                        'name' => $characterData['name'],
                        'air_date' => $characterData['air_date'],
                        'episode' => $characterData['episode'],
                        'url' => $characterData['url'],
                        'characters' => $characterData['characters'],
                        'created' => $characterData['created']
                    ]);

                    $this->info("Episódio '{$characterData['episode']}' cadastrado com sucesso.");
                } else {
                    $this->info("Episódio '{$characterData['episode']}' já está cadastrado.");
                }
            } else {
                $this->error("Erro ao acessar o Episódio com ID {$id}.");
            }
        }

        $this->info('Importação concluída com sucesso!');
    }
}