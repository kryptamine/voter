<?php

namespace App\Http\Requests;

use App\Http\DTO\PollCreateDto;
use Illuminate\Foundation\Http\FormRequest;

class PollRequest extends FormRequest
{
    public function toDto(): PollCreateDto
    {
        return PollCreateDto::fromArray($this->validated());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:0|max:255',
            'answers' => 'required|array|between:2,100',
            'answers.*' => 'required|string|min:0|max:255',
        ];
    }
}
