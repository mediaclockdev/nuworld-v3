<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class ApiProductVariantIDRequest extends BaseRequest
{
  protected $decodedId;

  protected function prepareForValidation(): void
  {
    $hash = $this->input('product_variant_id');

    // Safely check that it's a non-empty string before decoding
    if (is_string($hash) && !empty($hash)) {
      $decoded = Hashids::decode($hash);
      $this->decodedId = $decoded[0] ?? null;

      if ($this->decodedId !== null) {
        // Override the original product_variant_id for validation
        $this->merge([
          'product_variant_id' => $this->decodedId,
        ]);
      }
    }
  }


  public function rules(): array
  {
    return [
      'product_variant_id' => [
        'required',
        'exists:product_variants,id,deleted_at,NULL',
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'product_variant_id.required' => __('validation.required', ['attribute' => 'Product Variant']),
      'product_variant_id.exists' => __('validation.exists', ['attribute' => 'Product Variant']),
    ];
  }

  public function decodedId(): ?int
  {
    return $this->decodedId;
  }
}
