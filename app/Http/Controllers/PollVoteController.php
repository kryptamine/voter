<?php

namespace App\Http\Controllers;

use App\Http\Events\UserVoted;
use App\Http\Requests\VoteRequest;
use App\Http\Resources\VoteResource;
use App\Http\Resources\VoteResourceCollection;
use App\Http\Services\Exceptions\AlreadyVotedException;
use App\Http\Services\Exceptions\HashDecryptException;
use App\Http\Services\PollService;
use App\Models\Poll;
use Illuminate\Contracts\Encryption\DecryptException;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class PollVoteController extends Controller
{
    /**
     * @param Poll $poll
     * @param VoteRequest $voteRequest
     * @param PollService $pollService
     * @return VoteResource
     * @throws Throwable
     */
    public function store(Poll $poll, VoteRequest $voteRequest, PollService $pollService): VoteResource
    {
        try {
            $voteResource = new VoteResource(
                $pollService->vote($poll, $voteRequest->toDto())
            );

            UserVoted::dispatch($voteResource);

            return $voteResource;
        } catch (HashDecryptException | DecryptException $_) {
            // Return 403 in case when invalid hash provided
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Access forbidden');
        } catch (AlreadyVotedException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Pure]
    public function index(Poll $poll): VoteResourceCollection
    {
        return new VoteResourceCollection($poll->votes);
    }
}
