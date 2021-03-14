<?php

namespace App\Http\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

final class VoteCreateDto implements Arrayable
{
    public function __construct(
        private string $hash,
        private string $name,
        private int $answerId
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (string)Arr::get($data, 'hash'),
            (string)Arr::get($data, 'name'),
            (int)Arr::get($data, 'answer_id')
        );
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    #[ArrayShape([
        'name' => 'string',
        'hash' => 'string',
        'answer_id' => 'int',
    ])]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'hash' => $this->hash,
            'answer_id' => $this->answerId,
        ];
    }
}
