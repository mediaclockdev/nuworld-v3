<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface CommonServiceInterface
{
  public function update(Request $request, string $modelClass, int $id = 0): JsonResponse; // Calls Model::store

  public function destroy(string $modelClass, int $id = 0): JsonResponse; // Calls Model::remove

  public function toggle(string $modelClass, int $id = 0): JsonResponse; // Calls Model::toggleStatus

  public function multidestroy(Request $request, string $modelClass): JsonResponse; // Calls Model::remove in loop
}
