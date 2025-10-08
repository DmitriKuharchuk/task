<?php

namespace App\Http\Requests\Click;

use Illuminate\Foundation\Http\FormRequest;

class ClickStatRequest extends FormRequest
{
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
            'from'  => 'required|date',
            'to'    => 'required|date',
            'sort'  => 'sometimes|string|in:count,offer_id,source',
            'order' => 'sometimes|string|in:asc,desc',
            'limit' => 'sometimes|integer|min:1|max:1000'
		];
	}
}
