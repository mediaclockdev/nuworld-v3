<?php

namespace App\Http\Controllers\Backend\ContactManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Support;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
  protected string $name;

  protected $model;
  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->name = 'contacts';
    $this->model = Support::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.contact-manage.contacts.index', ['cardHeader' => $this->name . ' List']);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }
  public function details($hashedId)
  {
    try {
      $id = Hashids::decode($hashedId)[0] ?? null;
      if (!$id) {
        return response()->json(['success' => false, 'message' => 'Invalid contact ID'], 400);
      }
      $contact = $this->model::findOrFail($id);
      return response()->json([
        'success' => true,
        'data' => [
          'name'       => $contact->first_name . ' ' . $contact->last_name,
          'email'      => $contact->email,
          'message'    => $contact->message,
          'created_at' => convertDate($contact->created_at),
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Contact not found'], 404);
    }
  }
}
