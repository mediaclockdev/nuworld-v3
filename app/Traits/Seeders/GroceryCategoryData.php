<?php

namespace App\Traits\Seeders;

use App\Traits\BaseCategoryDataTrait;

class GroceryCategoryData
{
  use BaseCategoryDataTrait;

  public function getNestedCategories(): array
  {
    return [
      'Fruits & Veggies' => [
        'Apples' => ['Red Apples', 'Green Apples'],
        'Avocados' => ['Hass Avocados', 'Green-Skin Avocados'],
        'Citrus' => ['Oranges', 'Lemons', 'Limes'],
        'Bananas & Plantains' => ['Cavendish Bananas', 'Plantains'],
        'Beans, Peas & Corn' => ['Green Beans', 'Peas', 'Sweet Corn'],
        'Broccoli & Cauliflower' => ['Broccoli Florets', 'Cauliflower Heads'],
        'Garlic, Onions & Leeks' => ['Garlic', 'Onions & Leeks'],
        'Grapes' => ['Red Grapes', 'Green Grapes'],
        'Herbs' => ['Basil', 'Parsley', 'Cilantro'],
        'Lettuce & Greens' => ['Romaine Lettuce', 'Spinach', 'Kale'],
        'Melons' => ['Watermelon', 'Cantaloupe'],
        'Mushrooms' => ['Button Mushrooms', 'Shiitake'],
        'Other Fruit' => ['Pears', 'Berries'],
        'Other Vegetables' => ['Asparagus', 'Brussels Sprouts'],
        'Potatoes, Yams & Tubers' => ['Russet Potatoes', 'Sweet Potatoes'],
        'Root Vegetables' => ['Carrots', 'Beets'],
        'Stone Fruit' => ['Peaches', 'Plums'],
        'Tomatoes' => ['Roma Tomatoes', 'Cherry Tomatoes'],
        'Tropical Fruit' => ['Mangoes', 'Pineapples'],
        'Cucumbers' => ['English Cucumbers', 'Persian Cucumbers'],
        'Eggplant & Squash' => ['Eggplant', 'Zucchini'],
        'Peppers' => ['Bell Peppers', 'Chili Peppers'],
      ],
      'Meat' => [
        'Goat Meat or Lamb' => ['Ground Goat', 'Lamb Chops'],
        'Chicken' => ['Chicken Breast', 'Chicken Thighs'],
      ],
      'Dairy & Refrigerated' => [
        'Butter & Margarine' => ['Salted Butter', 'Margarine'],
        'Cheese' => ['Cheddar', 'Mozzarella'],
        'Cottage Cheese' => ['Regular Cottage Cheese', 'Low-Fat Cottage Cheese'],
        'Cream' => ['Heavy Cream', 'Whipping Cream'],
        'Creamers' => ['Dairy Creamer', 'Non-Dairy Creamer'],
        'Eggs & Egg Substitutes' => ['Chicken Eggs', 'Egg Whites'],
        'Milk' => ['Whole Milk', 'Skim Milk'],
        'Refrigerated Snacks' => ['Hummus', 'Cheese Sticks'],
        'Yogurt' => ['Greek Yogurt', 'Flavored Yogurt'],
      ],
      'Prepared Foods' => [
        'Appetizers & Sides' => ['Spring Rolls', 'Potato Salad'],
      ],
      'Pantry' => [
        'Baking Essentials' => ['Flour', 'Sugar'],
        'Breakfast Foods & Cereal' => ['Oats', 'Cereal Bars'],
        'Canned Goods' => ['Canned Vegetables', 'Canned Fruits'],
        'Condiments & Sauces' => ['Ketchup', 'Soy Sauce'],
        'Desserts & Toppings' => ['Chocolate Syrup', 'Whipped Cream'],
        'Herbs & Spices' => ['Paprika', 'Cumin'],
        'Pasta & Noodles' => ['Spaghetti', 'Ramen'],
        'Jams, Jellies & Spreads' => ['Strawberry Jam', 'Peanut Butter'],
        'Dried Fruit, Nuts & Seeds' => ['Almonds', 'Raisins'],
        'Rice, Beans & Grains' => ['White Rice', 'Lentils'],
        'Snack Food' => ['Chips', 'Popcorn'],
        'Sugar & Sweeteners' => ['Granulated Sugar', 'Honey'],
        'Coffee, Tea & Cocoa' => ['Ground Coffee', 'Black Tea'],
        'Seasoning Mixes' => ['Taco Seasoning', 'Curry Powder'],
        'Pickles' => ['Dill Pickles', 'Sweet Pickles'],
        'Honey' => ['Raw Honey', 'Clover Honey'],
        'Paste and Molasses' => ['Tomato Paste', 'Molasses'],
        'Other Pantry' => ['Soup Mixes', 'Instant Noodles'],
        'Oils & Vinegar' => ['Olive Oil', 'Balsamic Vinegar'],
      ],
      'Breads & Bakery' => [
        'Loaves' => ['White Bread', 'Whole Wheat Bread'],
        'Other Baked Goods' => ['Bagels', 'Croissants'],
        'Pastries' => ['Danishes', 'Puff Pastries'],
        'Rolls' => ['Dinner Rolls', 'Hamburger Rolls'],
        'Cookies' => ['Chocolate Chip Cookies', 'Oatmeal Cookies'],
        'Tortillas, Pita & Flatbreads' => ['Flour Tortillas', 'Pita Bread'],
        'Buns' => ['Hot Dog Buns', 'Brioche Buns'],
      ],
      'Frozen Foods' => [
        'Bread & Baked Goods' => ['Frozen Bread', 'Frozen Pastries'],
        'Breakfast Foods' => ['Frozen Waffles', 'Frozen Pancakes'],
        'Desserts' => ['Frozen Cakes', 'Frozen Pies'],
        'Fruits & Veggies' => ['Frozen Berries', 'Frozen Peas'],
        'Ice Cream & Treats' => ['Vanilla Ice Cream', 'Ice Pops'],
        'Meals & Entrees' => ['Frozen Lasagna', 'Frozen Stir-Fry'],
        'Frozen Pizza' => ['Cheese Pizza', 'Pepperoni Pizza'],
        'Appetizers, Snacks & Sides' => ['Frozen Mozzarella Sticks', 'Frozen Fries'],
        'Other Frozen Foods' => ['Frozen Dumplings', 'Frozen Burritos'],
      ],
      'Beverages' => [
        'Instant Mixes' => ['Lemonade Mix', 'Hot Chocolate Mix'],
        'Carbonated Drinks' => ['Cola', 'Ginger Ale'],
        'Juice & Juice Drinks' => ['Orange Juice', 'Apple Juice'],
        'Milk & Yogurt Drinks' => ['Chocolate Milk', 'Yogurt Smoothies'],
        'Non-Carbonated' => ['Iced Tea', 'Lemonade'],
        'Tea' => ['Green Tea', 'Herbal Tea'],
        'Water' => ['Spring Water', 'Sparkling Water'],
        'Coconut & Aloe Water' => ['Coconut Water', 'Aloe Vera Drink'],
        'Soda' => ['Root Beer', 'Lemon-Lime Soda'],
      ],
      'Sweets' => [
        'Chocolate' => ['Milk Chocolate', 'Dark Chocolate'],
        'Other Sweets' => ['Gummies', 'Hard Candy'],
        'Halva' => ['Sesame Halva', 'Nut Halva'],
      ],
      'Household' => [
        'Other Household Products' => ['Batteries', 'Light Bulbs'],
        'Cleaning Supplies' => ['All-Purpose Cleaner', 'Dish Soap'],
        'Cooking Essentials' => ['Aluminum Foil', 'Parchment Paper'],
        'Tableware' => ['Disposable Plates', 'Cutlery'],
      ],
      'Health & Wellness' => [
        'Cold, Allergy & Pain Relief' => ['Ibuprofen', 'Antihistamines'],
        'Essential Oils' => ['Lavender Oil', 'Peppermint Oil'],
        'Oral Care' => ['Toothpaste', 'Mouthwash'],
        'Personal Well-Being' => ['Hand Sanitizer', 'Face Masks'],
        'Vitamins & Supplements' => ['Multivitamins', 'Vitamin D'],
      ],
      'Beauty & Cosmetics' => [
        'Skincare' => ['Moisturizers', 'Cleansers'],
        'Hair Products' => ['Shampoo', 'Conditioner'],
      ],
      'Baby' => [
        'Baby Food' => ['Purees', 'Cereals'],
      ],
    ];
  }
}
