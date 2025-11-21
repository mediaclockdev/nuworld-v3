<?php

namespace App\Http\Requests\Backend\Promotions;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\DB;

class PromotionRequest extends BaseRequest
{
  public function rules(): array
  {
    $id = $this->route('id');

    return [
      'name' => ['required', 'string', 'max:255', 'unique:promotions,name,' . $id . ',id,deleted_at,NULL'],
      'promotion_start_from' => ['required', 'date'],
      'promotion_end_to' => ['required', 'date', 'after_or_equal:promotion_start_from'],

      'description' => ['nullable', 'string', 'max:500'],
      'promotion_mode' => ['required'],
      'product_id' => ['required'],
      'product_variant_id' => [
        'required',
        'array',
        'min:1',
        function ($attribute, $value, $fail) use ($id) {
          $startDate = $this->input('promotion_start_from');
          $endDate = $this->input('promotion_end_to');

          $conflict = DB::table('promotion_details')
            ->join('promotions', 'promotion_details.promotion_id', '=', 'promotions.id')
            ->whereNull('promotions.deleted_at')
            ->where('promotions.id', '!=', $id)
            ->where(function ($query) use ($startDate, $endDate) {
              $query->whereBetween('promotions.promotion_start_from', [$startDate, $endDate])
                ->orWhereBetween('promotions.promotion_end_to', [$startDate, $endDate])
                ->orWhere(function ($q) use ($startDate, $endDate) {
                  $q->where('promotions.promotion_start_from', '<=', $startDate)
                    ->where('promotions.promotion_end_to', '>=', $endDate);
                });
            })
            ->whereIn('promotion_details.product_variant_id', (array) $value)
            ->exists();

          if ($conflict) {
            $fail('A promotion already exists for one or more selected variants in the given date range.');
          }
        },
      ],
      'product_variant_id.*' => ['required'],
      'type' => ['required', 'in:Flat,Percentage'],

      'discount_amount' => [
        'required',
        'numeric',
        'min:0.01',
        function ($attribute, $value, $fail) {
          $type = $this->input('type');
          $variantIds = (array) $this->input('product_variant_id');

          $variants = DB::table('product_variants')
            ->whereIn('id', $variantIds)
            ->pluck('regular_price', 'id');

          foreach ($variants as $variantId => $price) {
            if ($type === 'Percentage' && $value > 100) {
              $fail("The maximum allowed discount percentage is 100%.");
              break;
            }

            if ($type === 'Flat' && $value > $price) {
              $fail("The discount amount cannot be greater than the regular price of any selected product variant.");
              break;
            }
          }
        },
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => __('validation.required', ['attribute' => 'Promotion Name']),
      'name.string' => __('validation.string', ['attribute' => 'Promotion Name']),
      'name.max' => __('validation.max', ['attribute' => 'Promotion Name', 'max' => 255]),
      'name.unique' => __('validation.unique', ['attribute' => 'Promotion Name']),

      'promotion_start_from.required' => __('validation.required', ['attribute' => 'Promotion Start From']),
      'promotion_start_from.date' => __('validation.date', ['attribute' => 'Promotion Start From']),

      'promotion_end_to.required' => __('validation.required', ['attribute' => 'Promotion End To']),
      'promotion_end_to.date' => __('validation.date', ['attribute' => 'Promotion End To']),
      'promotion_end_to.after_or_equal' => __('validation.after_or_equal', ['attribute' => 'Promotion End To', 'date' => 'Promotion Start From']),

      'description.nullable' => __('validation.nullable', ['attribute' => 'Description']),
      'description.string' => __('validation.string', ['attribute' => 'Description']),
      'description.max' => __('validation.max', ['attribute' => 'Description', 'max' => 500]),

      'promotion_mode.required' => __('validation.required', ['attribute' => 'Promotion Mode']),
      'product_id.required' => __('validation.required', ['attribute' => 'Product ID']),
      'product_variant_id.required' => __('validation.required', ['attribute' => 'Product Variant ID']),
      'product_variant_id.array' => __('validation.array', ['attribute' => 'Product Variant ID']),
      'product_variant_id.min' => __('validation.min', ['attribute' => 'Product Variant ID', 'min' => 1]),
      'product_variant_id.*.required' => __('validation.required', ['attribute' => 'Product Variant ID']),

      'type.required' => __('validation.required', ['attribute' => 'Discount Type']),
      'type.in' => __('validation.in', ['attribute' => 'Discount Type']),

      'discount_amount.required' => __('validation.required', ['attribute' => 'Discount Amount']),
      'discount_amount.numeric' => __('validation.numeric', ['attribute' => 'Discount Amount']),
      'discount_amount.min' => __('validation.min', ['attribute' => 'Discount Amount', 'min' => '0.01']),
    ];
  }

  protected function prepareForValidation(): void
  {
    $this->merge([
      'promotion_start_from' => $this->formatDate($this->promotion_start_from),
      'promotion_end_to' => $this->formatDate($this->promotion_end_to),
      'valid_from' => $this->formatDate($this->valid_from),
      'valid_to' => $this->formatDate($this->valid_to),
    ]);
  }

  private function formatDate(?string $date): ?string
  {
    if (!$date) return null;

    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
      [$d, $m, $y] = explode('/', $date);
      return "$y-$m-$d";
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $date)) {
      return str_replace('T', ' ', $date) . ':00';
    }

    return $date;
  }
}
