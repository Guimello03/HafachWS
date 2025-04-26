<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guardian>
 */
class GuardianFactory extends Factory
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
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'phone' => $this->faker->phoneNumber('##-#####-####'),
            'email' => $this->faker->unique()->safeEmail(),
            'birth_date' => $this->faker->date('Y-m-d', '+20 years'),
            'photo_path' => null,
            
        ];
    }
}
