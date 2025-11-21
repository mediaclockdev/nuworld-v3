<?php

namespace App\View\Components;

use App\Models\Address;
use App\Models\Country;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddressForm extends Component
{
  public string $address1 = '';
  public string $address2 = '';
  public string $landmark = '';
  public string $city = '';
  public int $state;
  public int $country_id;
  public $countries = [];
  public string $zip = '';
  /**
   * Create a new component instance.
   */
  public function __construct()
  {
    $getAddress = Address::where('admin_id', user('admin')->id)->first();
    $this->address1 = $getAddress->address_1 ?? '';
    $this->address2 = $getAddress->address_2 ?? '';
    $this->landmark = $getAddress->landmark ?? '';
    $this->city = $getAddress->city ?? '';
    $this->state = $getAddress->state_id ?? 0;
    $this->country_id = $getAddress->country_id ?? (config('defaults.country_id') ?? 0);
    $this->zip = $getAddress->pin ?? '';
    $this->countries = Country::all();
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.address-form');
  }
}
