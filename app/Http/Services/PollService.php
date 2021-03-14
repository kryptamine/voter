<?php

namespace App\Http\Services;

use App\Http\DTO\PollCreateDto;
use App\Http\DTO\VoteCreateDto;
use App\Http\Events\PollVoted;
use App\Http\Resources\VoteResource;
use App\Http\Services\Exceptions\AlreadyVotedException;
use App\Http\Services\Exceptions\HashDecryptException;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class PollService
{
    public function create(PollCreateDto $pollCreateDto): Poll
    {
        return DB::transaction(static function () use ($pollCreateDto): Poll {
            /** @var Poll $question */
            $question = Poll::create([
                'name' => $pollCreateDto->getName(),
                'uuid' => $pollCreateDto->getUuid(),
            ]);

            $answers = collect($pollCreateDto->getAnswers())
                ->map(fn(string $answer) => ['name' => $answer])
                ->toArray();

            $question->answers()->createMany($answers);

            return $question;
        });
    }

    /**
     * @param Poll $poll
     * @param VoteCreateDto $voteCreateDto
     * @return Vote
     * @throws AlreadyVotedException
     * @throws HashDecryptException
     * @throws Throwable
     */
    public function vote(Poll $poll, VoteCreateDto $voteCreateDto): Vote
    {
        try {
            if ($poll->votes()->whereHash($voteCreateDto->getHash())->exists()) {
                throw new AlreadyVotedException('You have already voted in the poll');
            }

            $vote = resolve(Vote::class);
            $vote->poll()
                ->associate($poll)
                ->fill($voteCreateDto->toArray())
                ->saveOrFail();

            return $vote;
        } catch (DecryptException $e) {
            throw new HashDecryptException($e->getMessage());
        }
    }
}
