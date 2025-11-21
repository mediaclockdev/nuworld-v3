<?php

namespace App\Services\Backend\System;

use App\Models\CustomerReward;
use App\Models\Product;
use App\Models\ScratchCardReward;
use App\Services\Backend\BaseFormService;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ScratchCardRewardService extends BaseFormService
{
  public function __construct()
  {
    parent::__construct(ScratchCardReward::class, 'Scratch Card Reward', 'reward');
  }

  /**
   * Prepare data for scratch card reward create form.
   *
   * @return array
   */
  public function getCreateData(): array
  {
    return [
      ...$this->getBaseCreateData(),
      'types' => ['fixed', 'percentage', 'coupon'],
      'products' => Product::all(),
    ];
  }

  /**
   * Prepare data for scratch card reward edit form.
   *
   * @param string $id
   * @return array
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function getEditData(string $id): array
  {
    return [
      ...$this->getBaseEditData($id),
      'types' => ['fixed', 'percentage', 'coupon'],
      'products' => Product::all(),
    ];
  }

  /**
   * Store a new scratch card reward.
   *
   * @param Request $request
   * @return array
   */
  public function storeData(Request $request)
  {
    $conditions = $this->prepareConditions($request);
    $request->merge(['conditions' => $conditions]);
    return ScratchCardReward::store($request);
  }

  /**
   * Update an existing scratch card reward.
   *
   * @param Request $request
   * @param int $id
   * @return array
   */
  public function updateData(Request $request, int $id)
  {
    $reward = ScratchCardReward::findOrFail($id);
    $conditions = $this->prepareConditions($request);
    $request->merge(['conditions' => $conditions]);
    return ScratchCardReward::store($request, $id);
  }

  /**
   * Prepare conditions JSON based on form input.
   *
   * @param Request $request
   * @return array
   */
  protected function prepareConditions(Request $request): array
  {
    $conditions = [];
    /* if ($request->input('type') === 'coupon' && $request->input('coupon_code')) {
      $conditions['coupon_code'] = strtoupper(trim($request->input('coupon_code')));
    } */
    if ($request->input('product_type') === 'any') {
      $conditions['product'] = 'any';
    } elseif ($request->input('product_type') === 'specific' && is_array($request->input('product_ids'))) {
      $conditions['product_ids'] = array_map('intval', $request->input('product_ids'));
    }
    return $conditions;
  }

  public function assignReward($order)
  {
    // Get product IDs from order_products joined with product_variants
    $orderProductIds = $order->orderProducts()
      ->join('product_variants', 'order_products.variant_id', '=', 'product_variants.id')
      ->pluck('product_variants.product_id')
      ->toArray();


    $today = now()->startOfDay();

    $rewards = ScratchCardReward::where('status', 1)->get()->filter(function ($reward) use ($orderProductIds, $today) {
      // These are Carbon instances already, no need to parse
      $startDate = $reward->valid_from->copy()->startOfDay();
      $expiryDate = $reward->valid_to->copy()->startOfDay();

      if (!$today->between($startDate, $expiryDate)) {
        return false; // Not valid today
      }

      if (isset($reward->conditions['product_ids'])) {
        return count(array_intersect($reward->conditions['product_ids'], $orderProductIds)) > 0;
      }

      if (isset($reward->conditions['product']) && $reward->conditions['product'] === 'any') {
        return true;
      }
      return false;
    });

    if ($rewards->count() > 1) {
      $specific = $rewards->filter(fn($reward) => isset($reward->conditions['product_ids']));
      if ($specific->isNotEmpty()) {
        $rewards = $specific;
      }
    }

    if ($rewards->isEmpty()) {
      return null;
    }

    // Randomly select a reward
    $selectedReward = $rewards->random();

    // Generate a unique scratch card code
    $scratchCardCode = strtoupper(Str::random(10));

    // Calculate expiry date based on validity_period
    $expiryDate = Carbon::now()->addDays($selectedReward->validity_period ?? 0)->endOfDay();

    $customerRewardCheck = CustomerReward::where('customer_id', auth()->id())->where('scratch_card_reward_id', $selectedReward->id)->first();
    if ($customerRewardCheck) {
      // $customerRewardCheck['exists'] = true;
      return $customerRewardCheck;
    }

    // Store in customer_rewards
    $customerReward = CustomerReward::create([
      'customer_id'            => auth()->id(),
      'scratch_card_reward_id' => $selectedReward->id,
      'scratch_card_code'      => $selectedReward->type === 'coupon' ? ($selectedReward->coupon_code ?? $scratchCardCode) : $scratchCardCode,
      'status'                 => 1,
      'expiry_date'            => $expiryDate,
      'order_id'               => $order->id
    ]);

    // Attach the reward and code for frontend display
    $customerReward->type = $selectedReward->type;
    $customerReward->value = $selectedReward->value;
    $customerReward->conditions = $selectedReward->conditions;
    $customerReward->scratch_card_code = $customerReward->scratch_card_code;

    return $customerReward;
  }

  public function updateExpiredRewards()
  {
    $today = Carbon::now()->toDateString();
    ScratchCardReward::whereDate('valid_to', '<', $today)
      ->update(['status' => 0]); // Set to Expired
  }
}
