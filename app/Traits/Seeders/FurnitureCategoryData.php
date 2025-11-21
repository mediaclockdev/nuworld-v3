<?php

namespace App\Traits\Seeders;

use App\Traits\BaseCategoryDataTrait;

class FurnitureCategoryData
{
  use BaseCategoryDataTrait;

  public function getNestedCategories(): array
  {
    return [
      'Mayuri Room' => [
        'Sofas' => ['Sectional Sofas', 'Sleeper Sofas'],
        'Recliners' => ['Power Recliners', 'Manual Recliners'],
        'Coffee Tables' => ['Glass Coffee Tables', 'Wood Coffee Tables'],
      ],
      'Bedroom' => [
        'Beds' => ['Queen Beds', 'King Beds'],
        'Nightstands' => ['Modern Nightstands', 'Wooden Nightstands'],
        'Dressers' => ['Double Dressers', 'Tall Dressers'],
      ],
      'Dining Room' => [
        'Dining Tables' => ['Round Dining Tables', 'Extendable Dining Tables'],
        'Dining Chairs' => ['Upholstered Chairs', 'Wooden Chairs'],
        'Bar Stools' => ['Adjustable Stools', 'Backless Stools'],
      ],
      'Office' => [
        'Desks' => ['Standing Desks', 'Computer Desks'],
        'Office Chairs' => ['Ergonomic Chairs', 'Executive Chairs'],
        'Bookcases' => ['Open Bookcases', 'Ladder Bookcases'],
      ],
      'Outdoor' => [
        'Patio Chairs' => ['Folding Chairs', 'Lounge Chairs'],
        'Outdoor Sofas' => ['Wicker Sofas', 'Modular Sofas'],
        'Dining Sets' => ['5-Piece Sets', '7-Piece Sets'],
      ],
      'Storage' => [
        'Storage Cabinets' => ['Metal Cabinets', 'Wood Cabinets'],
        'Shelves' => ['Wall Shelves', 'Corner Shelves'],
        'Closets' => ['Portable Closets', 'Built-in Closets'],
      ],
      'Decor' => [
        'Wall Art' => ['Canvas Prints', 'Framed Prints'],
        'Rugs' => ['Area Rugs', 'Runner Rugs'],
        'Lamps' => ['Table Lamps', 'Floor Lamps'],
      ],
      'Kids' => [
        'Kids Beds' => ['Bunk Beds', 'Toddler Beds'],
        'Toy Storage' => ['Toy Chests', 'Storage Bins'],
        'Kids Chairs' => ['Bean Bags', 'Mini Rocking Chairs'],
      ],
      'Entryway' => [
        'Coat Racks' => ['Standing Racks', 'Wall-mounted Racks'],
        'Shoe Cabinets' => ['Two-Door Cabinets', 'Slim Cabinets'],
        'Benches' => ['Storage Benches', 'Wooden Benches'],
      ],
      'Kitchen' => [
        'Kitchen Cabinets' => ['Base Cabinets', 'Wall Cabinets'],
        'Bar Stools' => ['Adjustable Stools', 'Backless Stools'],
        'Kitchen Tables' => ['Drop-leaf Tables', 'Round Tables'],
      ],
    ];
  }
}
