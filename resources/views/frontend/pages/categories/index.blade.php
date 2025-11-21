@extends('frontend.layouts.category')
@section('title', @$title)
@section('category-content')
  @include('frontend.includes.category_slider', [
      'categories' => $popularCategories,
      'categoryHeadlineBanner' => $categoryHeadlineBanner,
  ])
@endsection
