<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HuggingFaceService
{
  protected $apiUrl = "https://api-inference.huggingface.co/models/facebook/blenderbot-400M-distill";

  public function query($message)
  {
    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . config('services.huggingface.api_key'),
    ])->post($this->apiUrl, [
      "inputs" => $message,
    ]);

    return $response->json();
  }
}
