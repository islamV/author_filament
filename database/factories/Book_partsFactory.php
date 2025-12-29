<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Book_partsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => rand(1, 5),
            'chapter' => $this->faker->word,
            'content' => $this->faker->paragraph,
            'is_published' => $this->faker->boolean,
        ];
    }
}
