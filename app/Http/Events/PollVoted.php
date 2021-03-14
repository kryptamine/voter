<?php

namespace App\Http\Events;

use App\Http\Resources\VoteResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use JetBrains\PhpStorm\Pure;

class PollVoted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    private const CHANNEL_NAME = 'poll';

    public function __construct(
        public VoteResource $vote,
        private string $uuid
    ) {}

    #[Pure]
    public function broadcastOn(): Channel
    {
        return new Channel(self::CHANNEL_NAME);
    }

    #[Pure]
    public function broadcastAs(): string
    {
        return sprintf('%s-%s.voted', self::CHANNEL_NAME, $this->uuid);
    }
}
