<?php

namespace App\Http\Requests;

use App\Http\DTO\VoteCreateDto;
use App\Models\Poll;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:0|max:255',
            'hash' => 'required|string|min:0|max:500',
            'answer_id' => [
                'required',
                'integer',
                Rule::exists('answers', 'id')->where(function (Builder $builder) {
                    /** @var Poll $poll */
                    $poll = $this->route('poll');

                    return $builder->wherePollId(optional($poll)->id);
                }),
            ],
        ];
    }

    /**
     * @return string
     */
    public function hash(): string
    {
        return decrypt($this->get('hash'), false);
    }

    public function validated(): array
    {
        $validated = parent::validated();

        return array_merge($validated, [
            'hash' => $this->hash(),
        ]);
    }

    public function toDto(): VoteCreateDto
    {
        return VoteCreateDto::fromArray($this->validated());
    }
}
