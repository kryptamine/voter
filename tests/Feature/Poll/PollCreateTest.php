<?php

namespace Tests\Feature\Poll;

use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PollCreateTest extends AbstractPollTestCase
{
    use WithFaker;

    public function testPositive(): void
    {
        $answers = [];

        foreach (range(0, 10) as $_) {
            $answers[] = $this->faker->regexify('[A-Za-z0-9]{20}');
        }

        $data = [
            'name' => $this->faker->name,
            'answers' => $answers,
        ];

        $this->post(sprintf('%s/polls', self::API_URL), $data)
            ->assertCreated()
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $this->assertDatabaseHas('polls', [
            'name' => $data['name'],
        ]);

        $this->assertDatabaseHas('answers', [
            'name' => $answers,
        ]);
    }


    public function validationDataProvider(): array
    {
        return [
            [[], ['name', 'answers']],
            [['name' => 'test'], ['answers']],

            // validate answers length rule, min: 2
            [['answers' => ['first']], ['name', 'answers']],
            [['answers' => ['first', 'second']], ['name']],
        ];
    }

    /**
     * @dataProvider validationDataProvider
     * @param array $requestData
     * @param array $expectedErrors
     */
    public function testValidation(array $requestData, array $expectedErrors): void
    {
        $this->json(Request::METHOD_POST, sprintf('%s/polls', self::API_URL), $requestData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);
    }
}
