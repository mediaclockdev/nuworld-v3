<?php

namespace App\Http\Requests\Backend\ContentManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Validator;

class StoreMediaGalleryRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'files.*' => ['required', 'file', 'mimes:jpg,png,gif,webp,jpeg,mp4'],
      'title' => 'nullable|string|max:255',
    ];
  }

  public function messages(): array
  {
    return [
      'files.*.required' => __('validation.required', ['attribute' => 'Media File']),
      'files.*.mimes' => __('validation.mimes', ['attribute' => 'Media File', 'values' => 'jpg,png,gif,webp,jpeg,mp4']),
      'title.max' => __('validation.maxlength', ['attribute' => 'Title', 'max' => '255']),
    ];
  }

  public function withValidator(Validator $validator): void
  {
    $validator->after(function ($validator) {
      if ($this->hasFile('files')) {
        foreach ($this->file('files') as $file) {
          $sizeMB = $file->getSize() / (1024 * 1024);
          $mimeType = $file->getMimeType();

          if (str_starts_with($mimeType, 'image') && $sizeMB > 2) {
            $validator->errors()->add('files', "{$file->getClientOriginalName()} exceeds 2 MB limit for images.");
          } elseif (str_starts_with($mimeType, 'video') && $sizeMB > 10) {
            $validator->errors()->add('files', "{$file->getClientOriginalName()} exceeds 10 MB limit for videos.");
          }
        }
      }
    });
  }
}
