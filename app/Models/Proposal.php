<?php

namespace App\Models;

use App\Enums\ProposalType;
use Database\Factories\ProposalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property ProposalType $type
 * @property string $abstract
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['title', 'type', 'abstract'])]
class Proposal extends Model
{
    /** @use HasFactory<ProposalFactory> */
    use HasFactory;

    /**
     * Get the user who submitted the proposal.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the AI reviews of the proposal.
     *
     * @return HasMany<ProposalReview, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProposalReview::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ProposalType::class,
        ];
    }
}
