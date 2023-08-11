<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'job_title' => $this->faker->word(),
            'salary' => $this->faker->randomDigit(),
            'department' => $this->faker->word(),
            'joined_date' => $this->faker->date()
        ];
    }
}
