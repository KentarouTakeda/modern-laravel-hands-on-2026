<?php

namespace Tests\Feature;

use App\Ai\Agents\ProposalReviewer;
use App\Enums\ProposalType;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Exceptions\ProviderOverloadedException;
use Laravel\Ai\Prompts\AgentPrompt;
use Tests\TestCase;

class ProposalReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        ProposalReviewer::fake()->preventStrayPrompts();
        $proposal = Proposal::factory()->create();

        $this->post(route('proposals.reviews.store', $proposal))->assertRedirect(route('login'));

        ProposalReviewer::assertNeverPrompted();
    }

    public function test_store_saves_the_ai_review_of_the_proposal(): void
    {
        ProposalReviewer::fake([[
            'score' => 8,
            'summary' => 'よくまとまっています。',
            'strengths' => ['テーマが明確'],
            'improvements' => ['具体例を増やす', 'タイトルを短く'],
        ]]);
        $proposal = Proposal::factory()->create([
            'title' => 'Wayfinder 入門',
            'type' => ProposalType::LightningTalk,
            'abstract' => '型を自動生成する話。',
        ]);

        $this->actingAs($proposal->user)
            ->post(route('proposals.reviews.store', $proposal))
            ->assertRedirect(route('proposals.show', $proposal));

        ProposalReviewer::assertPrompted(fn (AgentPrompt $prompt) => $prompt->contains('種別: LT')
            && $prompt->contains('タイトル: Wayfinder 入門')
            && $prompt->contains('型を自動生成する話。'));

        $review = $proposal->reviews()->sole();
        $this->assertSame(8, $review->score);
        $this->assertSame('よくまとまっています。', $review->summary);
        $this->assertSame(['テーマが明確'], $review->strengths);
        $this->assertSame(['具体例を増やす', 'タイトルを短く'], $review->improvements);
    }

    public function test_store_does_not_save_a_review_when_the_ai_request_fails(): void
    {
        ProposalReviewer::fake(fn () => throw new ProviderOverloadedException('Overloaded'));
        $proposal = Proposal::factory()->create();

        $this->actingAs($proposal->user)
            ->post(route('proposals.reviews.store', $proposal))
            ->assertRedirect(route('proposals.show', $proposal));

        $this->assertDatabaseEmpty('proposal_reviews');
    }

    public function test_another_users_proposal_cannot_be_reviewed(): void
    {
        ProposalReviewer::fake()->preventStrayPrompts();
        $proposal = Proposal::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('proposals.reviews.store', $proposal))
            ->assertNotFound();

        ProposalReviewer::assertNeverPrompted();
        $this->assertDatabaseEmpty('proposal_reviews');
    }
}
