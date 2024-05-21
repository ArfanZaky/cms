<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebContacts>
 */
class WebContactsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => 1,
            'subject' => $this->faker->sentence(3),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'description' => $this->faker->text(200),
            'status' => $this->faker->randomElement(['0', '1']),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
