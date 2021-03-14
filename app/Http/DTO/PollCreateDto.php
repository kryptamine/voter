<?php

namespace App\Http\DTO;

use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

final class PollCreateDto
{
    private string $uuid;

    /**
     * PollCreateDTO constructor.
     * @param string $name
     * @param string[] $answers
     */
    public function __construct(
        private string $name,
        private array $answers,
    ) {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string)Arr::get($data, 'name'),
            (array)Arr::get($data, 'answers', [])
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }
}
