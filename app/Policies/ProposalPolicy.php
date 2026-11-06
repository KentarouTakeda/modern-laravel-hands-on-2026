<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProposalPolicy
{
    /**
     * Determine whether the user can view the proposal.
     */
    public function view(User $user, Proposal $proposal): Response
    {
        return $this->ownsProposal($user, $proposal);
    }

    /**
     * Determine whether the user can update the proposal.
     */
    public function update(User $user, Proposal $proposal): Response
    {
        return $this->ownsProposal($user, $proposal);
    }

    /**
     * Determine whether the user can delete the proposal.
     */
    public function delete(User $user, Proposal $proposal): Response
    {
        return $this->ownsProposal($user, $proposal);
    }

    /**
     * Allow only the proposal's owner, hiding the proposal's existence from everyone else.
     */
    private function ownsProposal(User $user, Proposal $proposal): Response
    {
        return $user->id === $proposal->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
