<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class TryOnPreviewRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
     public function messages(): array
    {
        return [
            //
        ];
    }


}
