<?php

namespace App\Http\Requests\CallLog;

use App\Traits\ReturnResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Enums\StatusCallLogEnum;
use Illuminate\Validation\Rules\Enum;

class CreateCallLogRequest extends FormRequest
{
    use ReturnResponser;
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
            'duration' => ['required', 'integer'],
            'status' => ['required', 'string', new Enum(StatusCallLogEnum::class)],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorvalidator($this->validator->errors()->messages()));
    }
}
