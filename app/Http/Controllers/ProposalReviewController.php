<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ProposalReviewer;
use App\Models\Proposal;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Laravel\Ai\Exceptions\AiException;

class ProposalReviewController extends Controller
{
    /**
     * Ask the AI to review the proposal and store the result.
     */
    public function store(Proposal $proposal): RedirectResponse
    {
        Gate::authorize('update', $proposal);

        try {
            $response = (new ProposalReviewer)->prompt(ProposalReviewer::promptFor($proposal));
        } catch (AiException|RequestException $exception) {
            report($exception);

            Inertia::flash('toast', ['type' => 'error', 'message' => __('AIによる講評に失敗しました。時間をおいて再度お試しください。')]);

            return to_route('proposals.show', $proposal);
        }

        $proposal->reviews()->create([
            'score' => $response['score'],
            'summary' => $response['summary'],
            'strengths' => $response['strengths'],
            'improvements' => $response['improvements'],
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('AIによる講評を保存しました。')]);

        return to_route('proposals.show', $proposal);
    }
}
