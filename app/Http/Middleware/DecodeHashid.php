<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Vinkla\Hashids\Facades\Hashids;

class DecodeHashid
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $hashid = $request->route('id'); // 'id' is supplied via route
    $decoded = Hashids::decode($hashid);

    if (empty($decoded)) {
      abort(404);
    }
    $request->merge($this->cleanInputs($request->all()));
    $request->route()->setParameter('id', $decoded[0]); // Replacing hashid with the decoded integer

    return $next($request);
  }

  protected function cleanInputs(array $inputs)
  {
    foreach ($inputs as $key => $value) {
      if (is_string($value)) {
        $inputs[$key] = trim($value); // Removing all leading/trailing spaces here
      } elseif (is_array($value)) {
        $inputs[$key] = $this->cleanInputs($value);
      }
    }
    return $inputs;
  }
}
