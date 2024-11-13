<?php

namespace Database\Factories;

use App\Models\EpisodeModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class EpisodeModelFactory extends Factory
{
    protected $model = EpisodeModel::class;

    /**
     * @github.com/AlexandreOsovski
     *
     * @return void
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'episode' => $this->faker->word(),
            'air_date' => $this->faker->date(),
            'url' => $this->faker->url(),
            'created' => $this->faker->dateTime(),
        ];
    }
}