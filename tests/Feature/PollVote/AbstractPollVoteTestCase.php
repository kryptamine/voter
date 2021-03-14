<?php

namespace Tests\Feature\PollVote;

use Tests\TestCase;

abstract class AbstractPollVoteTestCase extends TestCase
{
    protected const JSON_STRUCTURE = [
        'data' => [[
            'name',
            'hash',
            'id',
            'answer' => [
                'id',
                'name',
            ],
        ]]
    ];
}
