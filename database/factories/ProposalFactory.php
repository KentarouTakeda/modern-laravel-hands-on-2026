<?php

namespace Database\Factories;

use App\Enums\ProposalType;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proposal>
 */
class ProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'type' => fake()->randomElement(ProposalType::cases()),
            'abstract' => fake()->paragraphs(2, true),
        ];
    }
}
