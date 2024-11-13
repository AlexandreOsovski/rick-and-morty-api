<?php

namespace Database\Factories;

use App\Models\CharacterModel;
use App\Models\LocationModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterModelFactory extends Factory
{
    protected $model = CharacterModel::class;

    /**
     * @github.com/AlexandreOsovski
     *
     * @return void
     */
    public function definition()
    {
        return [
            'character_api_id' => $this->faker->unique()->numberBetween(1, 1000),
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement(['Alive', 'Dead', 'Unknown']),
            'species' => $this->faker->randomElement(['Human', 'Alien', 'Robot']),
            'type' => $this->faker->word,
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Genderless']),
            'origin_id' => LocationModel::factory(),
            'location_id' => LocationModel::factory(),
            'image' => $this->faker->imageUrl(),
            'episode' => $this->faker->randomElements([
                'S01E01',
                'S02E03',
                'S03E04',
                'S04E05',
                'S05E06'
            ], 2),
            'url' => $this->faker->url,
            'created' => $this->faker->dateTimeThisDecade(),
        ];
    }
}