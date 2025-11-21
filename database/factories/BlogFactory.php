<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $title = $this->faker->sentence(6, true);

    return [
      'title' => $title,
      'slug' => Str::slug($title),
      'post_id' => 1, // adjust according to existing post_ids
      'image' => 'dummy.jpg', // or $this->faker->imageUrl()
      'short_description' => $this->faker->paragraph(),
      'long_description' => $this->faker->paragraphs(3, true),
      'status' => rand(0, 1),
      'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
      'created_by' => 1, // or rand(1, X)
      'updated_by' => 1,
      'deleted_by' => null,
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
