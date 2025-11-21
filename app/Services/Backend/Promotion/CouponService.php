<?php

namespace App\Services\Backend\Promotion;

use App\Models\Coupon;
use App\Services\Backend\BaseFormService;

class CouponService extends BaseFormService
{
  public function __construct(string $modelClass = Coupon::class, string $displayName = 'Coupon', string $variableName = 'coupon')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }
  /**
   * Prepare data for promotion create form.
   *
   * @return array
   */
  public function getCreateData(): array
  {
    return [
      ...$this->getBaseCreateData(),
    ];
  }

  /**
   * Prepare data for promotion edit form.
   *
   * @param string $id
   * @return array
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function getEditData(string $id): array
  {
    $coupon = $this->modelClass::findOrFail($id);
    if (!$coupon) {
      abort(404, 'Coupon not found.');
    }
    return [
      ...$this->getBaseEditData($id),
    ];
  }
}
