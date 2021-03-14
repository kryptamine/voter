<?php

namespace Tests\Feature\PollVote;

use App\Models\Poll;

class PollVoteListTest extends AbstractPollVoteTestCase
{
    public function testPositive(): void
    {
        $poll = Poll::factory()->hasVotes(3)->create();

        $this->get(sprintf('%s/polls/%s/votes', self::API_URL, $poll->uuid))
            ->assertOk()
            ->assertJsonStructure(self::JSON_STRUCTURE);
    }

    public function testNonExistentId(): void
    {
        $this->get(sprintf('%s/polls/%s/votes', self::API_URL, 404))
            ->assertNotFound();
    }
}
