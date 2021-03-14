<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PollRequest;
use App\Http\Resources\PollResource;
use App\Http\Services\PollService;
use App\Models\Poll;
use JetBrains\PhpStorm\Pure;

class PollController extends Controller
{
    #[Pure]
    public function show(Poll $poll): PollResource
    {
        return new PollResource($poll);
    }

    public function store(PollRequest $request, PollService $pollService): PollResource
    {
        return new PollResource($pollService->create($request->toDto()));
    }
}
