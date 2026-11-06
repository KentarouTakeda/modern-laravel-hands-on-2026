<?php

namespace App\Enums;

enum ProposalType: string
{
    case Talk = 'talk';
    case LightningTalk = 'lightning_talk';
    case Workshop = 'workshop';

    /**
     * Get the human-readable label for the proposal type.
     */
    public function label(): string
    {
        return match ($this) {
            self::Talk => 'トーク',
            self::LightningTalk => 'LT',
            self::Workshop => 'ワークショップ',
        };
    }
}
