<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ClickRequest extends FormRequest {
  public function authorize(): bool
  {
      return true;
  }

  public function rules(): array {
	return [
        'click_id'=>'required|string',
        'offer_id'=>'required|integer',
        'source'=>'required|string',
        'timestamp'=>'required|date',
        'signature'=>'required|string|size:64',
        'url'=>'sometimes|string',
        'ua'=>'sometimes|string',
        'ip'=>'sometimes|ip',
	];
  }

}
