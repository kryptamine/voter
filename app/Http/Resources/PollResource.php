<?php

namespace App\Http\Resources;

use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PollResource
 * @package App\Http\Resources
 * @mixin Poll
 */
class PollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'uuid' => $this->uuid,
            'answers' => new AnswerResourceCollection($this->answers),
        ];
    }
}
