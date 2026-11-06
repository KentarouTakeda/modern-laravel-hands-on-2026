<?php

namespace App\Models;

use Database\Factories\ProposalReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $proposal_id
 * @property int $score
 * @property string $summary
 * @property list<string> $strengths
 * @property list<string> $improvements
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['score', 'summary', 'strengths', 'improvements'])]
class ProposalReview extends Model
{
    /** @use HasFactory<ProposalReviewFactory> */
    use HasFactory;

    /**
     * Get the proposal that was reviewed.
     *
     * @return BelongsTo<Proposal, $this>
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'strengths' => 'array',
            'improvements' => 'array',
        ];
    }
}
