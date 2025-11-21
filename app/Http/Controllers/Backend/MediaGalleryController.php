<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ContentManage\StoreMediaGalleryRequest;
use App\Models\MediaGallery;
use App\Services\ImageUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaGalleryController extends Controller
{
  protected $imageUploadService;
  protected string $name;
  protected $model;
  public function __construct(ImageUploadService $imageUploadService)
  {
    $this->name = 'Media Gallery';
    $this->model = MediaGallery::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
    $this->imageUploadService = $imageUploadService;
  }
  public function index(): View
  {
    return view('backend.pages.content-manage.mediagallery.index', ['cardHeader' => $this->name . ' List']);
  }
  public function create()
  {
    $mediaItems = $this->model::latest()->paginate(12);

    $mediaItems->getCollection()->transform(function ($item) {
      $item->url = Storage::url($item->file_name);
      return $item;
    });
    return view('backend.pages.content-manage.mediagallery.form', ['mediaItems' => $mediaItems]);
  }

  public function store(StoreMediaGalleryRequest $request)
  {
    $directory = 'media';
    $files = $request->file('files');
    foreach ($files as $file) {
      $extension = $file->getClientOriginalExtension();
      $filename = uniqid() . '.' . $extension;
      $mimeType = $file->getMimeType();

      if (str_starts_with($mimeType, 'image')) {
        $upload = $this->imageUploadService->uploadImage($file, $directory, 'images', true);
      } else {
        $subDirectory = str_starts_with($mimeType, 'video') ? 'videos' : 'files';
        $upload = Storage::disk('public')->putFileAs("/uploads/{$directory}/{$subDirectory}", $file, $filename);
        $upload = ['filename' => $filename];
      }

      MediaGallery::create([
        'title' => $request->title,
        'file_name' => $upload['filename'],
        'file_type' => $mimeType,
        'created_by' => user('admin')->id,
      ]);
    }

    return response()->json(['success' => true, 'message' => __('response.success.upload', ['item' => 'Files'])]);
  }
}
