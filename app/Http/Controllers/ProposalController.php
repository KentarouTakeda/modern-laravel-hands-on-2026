<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalRequest;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ProposalController extends Controller
{
    /**
     * Show the user's proposals.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('proposals/index', [
            'proposals' => Proposal::query()
                ->whereBelongsTo($request->user())
                ->latest()
                ->latest('id')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a proposal.
     */
    public function create(): Response
    {
        return Inertia::render('proposals/create');
    }

    /**
     * Store a new proposal for the user.
     */
    public function store(ProposalRequest $request): RedirectResponse
    {
        $proposal = $request->user()->proposals()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('プロポーザルを作成しました。')]);

        return to_route('proposals.show', $proposal);
    }

    /**
     * Show a proposal.
     */
    public function show(Proposal $proposal): Response
    {
        Gate::authorize('view', $proposal);

        return Inertia::render('proposals/show', [
            'proposal' => $proposal,
        ]);
    }

    /**
     * Show the form for editing a proposal.
     */
    public function edit(Proposal $proposal): Response
    {
        Gate::authorize('update', $proposal);

        return Inertia::render('proposals/edit', [
            'proposal' => $proposal,
        ]);
    }

    /**
     * Update a proposal.
     */
    public function update(ProposalRequest $request, Proposal $proposal): RedirectResponse
    {
        Gate::authorize('update', $proposal);

        $proposal->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('プロポーザルを更新しました。')]);

        return to_route('proposals.show', $proposal);
    }

    /**
     * Delete a proposal.
     */
    public function destroy(Proposal $proposal): RedirectResponse
    {
        Gate::authorize('delete', $proposal);

        $proposal->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('プロポーザルを削除しました。')]);

        return to_route('proposals.index');
    }
}
