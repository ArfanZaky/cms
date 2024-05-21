<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebVacancyApplications>
 */
class WebVacancyApplicationsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'vacancy_id' => 1,
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElement,
            'dob' => $this->faker->date('Y-m-d', 'now'),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'province' => $this->faker->state,
            'city' => $this->faker->city,
            'postal' => $this->faker->postcode,
            'address' => $this->faker->address,
            'education' => $this->faker->randomElement(['SMA', 'D3', 'S1', 'S2', 'S3']),
            'university' => $this->faker->company,
            'major' => $this->faker->jobTitle,
            'gpa' => $this->faker->randomFloat(2, 0, 4),
            'url' => $this->faker->url,
            'status' => $this->faker->randomElement(['0', '1']),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now'),

        ];
    }
}
