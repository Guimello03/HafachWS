<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'registration_number' => $this->faker->unique()->numerify('MAT-#####'),
            'birth_date' => $this->faker->date('Y-m-d', '-20 years'),
            'photo_path' => null,
            'guardian_id' => null,
        ];
    }
}
