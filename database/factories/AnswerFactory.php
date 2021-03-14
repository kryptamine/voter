<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Poll;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'poll_id' => Poll::factory(),
            'name' => $this->faker->name,
        ];
    }
}
