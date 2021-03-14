<?php

namespace Tests\Feature\Poll;

use Tests\TestCase;

abstract class AbstractPollTestCase extends TestCase
{
    protected const JSON_STRUCTURE = [
        'data' => [
            'name',
            'uuid',
            'answers',
            'answers' => [
                '*' => [
                    'id',
                    'name'
                ],
            ],
        ]
    ];
}
