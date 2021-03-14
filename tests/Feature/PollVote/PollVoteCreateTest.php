<?php

namespace Tests\Feature\PollVote;

use App\Http\Events\PollVoted;
use App\Models\Poll;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PollVoteCreateTest extends AbstractPollVoteTestCase
{
    use WithFaker;

    public function testPositive(): void
    {
        Event::fake(PollVoted::class);

        $poll = Poll::factory()->hasAnswers(2)->create();
        $data = [
            'name' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'hash' => encrypt($this->faker->uuid),
            'answer_id' => $poll->answers->first()->id,
        ];

        $this->post(sprintf('%s/polls/%s/votes', self::API_URL, $poll->uuid), $data)
            ->assertCreated()
            ->assertJsonStructure([
                'data' => self::JSON_STRUCTURE['data'][0],
            ]);

        $this->assertDatabaseHas('votes', [
            'name' => $data['name'],
            'answer_id' => $data['answer_id'],
            'hash' => decrypt($data['hash'], false),
        ]);

        Event::assertDispatched(PollVoted::class);
    }

    public function testForbiddenOnInvalidHash(): void
    {
        $poll = Poll::factory()->hasAnswers(2)->create();
        $data = [
            'name' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'hash' => $this->faker->uuid,
            'answer_id' => $poll->answers->first()->id,
        ];

        $this->post(sprintf('%s/polls/%s/votes', self::API_URL, $poll->uuid), $data)
            ->assertForbidden();
    }

    public function testAlreadyVoted(): void
    {
        $poll = Poll::factory()
            ->hasAnswers(2)
            ->hasVotes(2)
            ->create();

        $data = [
            'name' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'hash' => encrypt($poll->votes->first()->hash, false),
            'answer_id' => $poll->answers->first()->id,
        ];

        $this->post(sprintf('%s/polls/%s/votes', self::API_URL, $poll->uuid), $data)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
