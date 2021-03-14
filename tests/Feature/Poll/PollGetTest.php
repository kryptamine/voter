<?php

namespace Tests\Feature\Poll;

use App\Models\Poll;

class PollGetTest extends AbstractPollTestCase
{
    public function testPositive(): void
    {
        $poll = Poll::factory()->hasAnswers(3)->create();

        $this->get(sprintf('%s/polls/%s', self::API_URL, $poll->uuid))
            ->assertOk()
            ->assertJsonStructure(self::JSON_STRUCTURE);
    }

    public function testNonExistentId(): void
    {
        $this->get(sprintf('%s/polls/%s', self::API_URL, 404))
            ->assertNotFound();
    }
}
