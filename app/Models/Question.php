<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'topic_id', 'title', 'description', 'choice_0', 'choice_1', 'choice_2', 'choice_3', 'answer', 'position',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function getChoicesAttribute(): array
    {
        return [
            $this->choice_0,
            $this->choice_1,
            $this->choice_2,
            $this->choice_3,
        ];
    }
}
