<?php

namespace Database\Factories;

use App\Models\Proposal;
use App\Models\ProposalReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProposalReview>
 */
class ProposalReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'proposal_id' => Proposal::factory(),
            'score' => fake()->numberBetween(1, 10),
            'summary' => fake()->paragraph(),
            'strengths' => fake()->sentences(2),
            'improvements' => fake()->sentences(2),
        ];
    }
}
