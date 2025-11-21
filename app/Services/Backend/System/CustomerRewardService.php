<?php

namespace App\Services\Backend\System;

use App\Models\CustomerReward;
use App\Services\Backend\BaseFormService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class CustomerRewardService extends BaseFormService
{
  public function __construct()
  {
    parent::__construct(CustomerReward::class, 'Customer Reward', 'reward');
  }

  /**
   * Prepare data for customer reward create form.
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
   * Get paginated customer rewards with search and sorting.
   *
   * @param string $search
   * @param string $sortField
   * @param string $sortDirection
   * @param int $perPage
   * @return \Illuminate\Pagination\LengthAwarePaginator
   */
  /* public function getListData(string $search = '', string $sortField = 'id', string $sortDirection = 'asc', int $perPage = 10)
  {
    return CustomerReward::query()
      ->with(['scratchCardReward', 'customer'])
      ->when($search, function ($query) use ($search) {
        $query->where('scratch_card_code', 'like', '%' . $search . '%')
          ->orWhereHas('customer', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
          })
          ->orWhereHas('scratchCardReward', function ($q) use ($search) {
            $q->where('type', 'like', '%' . $search . '%')
              ->orWhere('conditions', 'like', '%' . $search . '%');
          });
      })
      ->orderBy($sortField, $sortDirection)
      ->paginate($perPage);
  } */


  public function updateStatus(int $id, int $status): array
  {
    $customerReward = CustomerReward::find($id);

    if (!$customerReward) {
      return [
        'success' => false,
        'message' => 'Customer Reward not found.',
      ];
    }

    $customerReward->status = $status;
    $customerReward->updated_by = user('admin')->id;
    $customerReward->save();

    return [
      'success' => true,
      'message' => 'Customer Reward status updated successfully.',
      'newStatus' => Hashids::encode($customerReward->status),
      'label' => $customerReward->status_text,
      'class' => $customerReward->status_class,
    ];
  }

  public function updateExpiredRewards()
  {
    $today = Carbon::now()->toDateString();
    CustomerReward::whereDate('expiry_date', '<', $today)
      ->update(['status' => 3]); // Set to Expired
  }
}
