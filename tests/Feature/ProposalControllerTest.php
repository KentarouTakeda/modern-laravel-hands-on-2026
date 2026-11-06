<?php

namespace Tests\Feature;

use App\Enums\ProposalType;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProposalControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get(route('proposals.index'))->assertRedirect(route('login'));
    }

    public function test_index_lists_only_the_users_own_proposals(): void
    {
        $user = User::factory()->create();
        $ownProposal = Proposal::factory()->for($user)->create();
        Proposal::factory()->create();

        $this->actingAs($user)
            ->get(route('proposals.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('proposals/index')
                ->has('proposals', 1)
                ->where('proposals.0.id', $ownProposal->id)
            );
    }

    public function test_create_page_is_displayed(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('proposals.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('proposals/create'));
    }

    public function test_store_creates_a_proposal_for_the_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('proposals.store'), [
            'title' => 'My Talk',
            'type' => 'lightning_talk',
            'abstract' => 'About my talk.',
        ]);

        $proposal = Proposal::sole();
        $response->assertSessionHasNoErrors()->assertRedirect(route('proposals.show', $proposal));
        $this->assertSame($user->id, $proposal->user_id);
        $this->assertSame('My Talk', $proposal->title);
        $this->assertSame(ProposalType::LightningTalk, $proposal->type);
        $this->assertSame('About my talk.', $proposal->abstract);
    }

    public function test_store_rejects_an_empty_payload(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('proposals.store'), [])
            ->assertSessionHasErrors([
                'title' => 'The title field is required.',
                'type' => 'The type field is required.',
                'abstract' => 'The abstract field is required.',
            ]);

        $this->assertDatabaseEmpty('proposals');
    }

    public function test_store_rejects_an_unknown_type(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('proposals.store'), [
                'title' => 'My Talk',
                'type' => 'keynote',
                'abstract' => 'About my talk.',
            ])
            ->assertSessionHasErrors(['type' => 'The selected type is invalid.']);

        $this->assertDatabaseEmpty('proposals');
    }

    public function test_show_displays_the_users_proposal(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs($proposal->user)
            ->get(route('proposals.show', $proposal))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('proposals/show')
                ->where('proposal.id', $proposal->id)
            );
    }

    public function test_edit_page_is_displayed_for_the_owner(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs($proposal->user)
            ->get(route('proposals.edit', $proposal))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('proposals/edit')
                ->where('proposal.id', $proposal->id)
            );
    }

    public function test_update_changes_the_proposal(): void
    {
        $proposal = Proposal::factory()->create(['type' => ProposalType::Talk]);

        $this->actingAs($proposal->user)
            ->put(route('proposals.update', $proposal), [
                'title' => 'Updated title',
                'type' => 'workshop',
                'abstract' => 'Updated abstract.',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('proposals.show', $proposal));

        $proposal->refresh();
        $this->assertSame('Updated title', $proposal->title);
        $this->assertSame(ProposalType::Workshop, $proposal->type);
        $this->assertSame('Updated abstract.', $proposal->abstract);
    }

    public function test_destroy_deletes_the_proposal(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs($proposal->user)
            ->delete(route('proposals.destroy', $proposal))
            ->assertRedirect(route('proposals.index'));

        $this->assertModelMissing($proposal);
    }

    public function test_another_users_proposal_cannot_be_viewed(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('proposals.show', $proposal))
            ->assertNotFound();
    }

    public function test_another_users_proposal_cannot_be_edited(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('proposals.edit', $proposal))
            ->assertNotFound();
    }

    public function test_another_users_proposal_cannot_be_updated(): void
    {
        $proposal = Proposal::factory()->create(['title' => 'Original title']);

        $this->actingAs(User::factory()->create())
            ->put(route('proposals.update', $proposal), [
                'title' => 'Hijacked',
                'type' => 'talk',
                'abstract' => 'Hijacked.',
            ])
            ->assertNotFound();

        $this->assertSame('Original title', $proposal->refresh()->title);
    }

    public function test_another_users_proposal_cannot_be_deleted(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('proposals.destroy', $proposal))
            ->assertNotFound();

        $this->assertModelExists($proposal);
    }
}
