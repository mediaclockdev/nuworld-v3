<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Vinkla\Hashids\Facades\Hashids;

class User extends Authenticatable implements JWTSubject
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, SoftDeletes, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'email',
    'password',
    'phone',
    'dob',
    'gender',
    'remember_token',
    'created_by',
    'updated_by',
    'status',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];
  protected $appends = ['gender_text'];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function getNameAttribute(): string
  {
    $names = array_filter([
      $this->first_name,
      $this->middle_name,
      $this->last_name,
    ]);

    return implode(' ', $names);
  }

  public function getStatusTextAttribute()
  {
    return match ($this->status) {
      1 => 'Active',
      2 => 'Revoked',
      default => 'Unknown',
    };
  }

  public function addresses()
  {
    return $this->hasMany(Address::class, 'user_id', 'id');
  }

  public function getFullNameAttribute()
  {
    $parts = array_filter([
      $this->first_name,
      $this->middle_name,
      $this->last_name,
    ]);

    return count($parts) ? implode(' ', $parts) : 'N/A';
  }


  public function userActivity()
  {
    return $this->hasMany(UserActivity::class, 'user_id');
  }
  public function orders()
  {
    return $this->hasMany(Order::class, 'user_id');
  }

  public static function store($request, int $id = 0)
  {
    $customer = $id ? self::find($id) : new self();

    if (!$customer) {
      return false; // Return false if user not found for update
    }

    $customer->fill([
      'first_name'  => $request->firstname,
      'middle_name' => $request->middlename,
      'last_name'   => $request->lastname,
      'email'       => $request->customeremail,
      'phone'       => $request->customerphone,
      'status'      => $request->status,
      'updated_by' => user('admin')->id
    ]);

    if (!$id) { // New user creation logic
      $password = Str::random(8);
      $customer->password = bcrypt($password);
      $customer->created_by = user('admin')->id;
    }

    if (!$customer->save()) { // If saving fails
      return false;
    }

    return $id ? true : $password; // Return password for new users, true for updates

  }


  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $update = self::find($id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Customer'])]);

    $update->status = $update->status == 1 ? 2 : 1;
    $update->updated_by = auth('admin')->id();
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Customer Status']), 'newStatus' => $update->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $user = self::find($id);
    if (!$user)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Customer'])]);

    $user->delete();
    $user->deleted_by = auth('admin')->id();
    $user->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Customer'])]);
  }

  public static function scopeSearch($query, $search)
  {
    if (!empty($search)) {
      $keywords = explode(' ', $search);

      return $query->where(function ($q) use ($keywords) {
        foreach ($keywords as $word) {
          $q->where(function ($subQuery) use ($word) {
            $subQuery->where('first_name', 'like', '%' . $word . '%')
              ->orWhere('last_name', 'like', '%' . $word . '%')
              ->orWhere('email', 'like', '%' . $word . '%')
              ->orWhere('phone', 'like', '%' . $word . '%');
          });
        }
      });
    }
  }

  public function completedOrders()
  {
    return $this->hasMany(Order::class)->where('order_status', 5);
  }

  public function hasCompletedOrderForVariant($variantId)
  {
    return $this->completedOrders()
      ->whereHas('orderProducts', function ($query) use ($variantId) {
        $query->where('variant_id', $variantId);
      })->exists();
  }
  public function getGenderTextAttribute()
  {
    $genderText = $this->attributes['gender'] ?? null;

    return match ($genderText) {
      1 => 'Male',
      2 => 'Female',
      3 => 'Others',
      default => 'N/A',
    };
  }

  public function getJWTIdentifier()
  {
    return Hashids::encode($this->id);
  }

  public function getJWTCustomClaims()
  {
    return [];
  }
}
