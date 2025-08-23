<?php

namespace Database\Factories;

use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 10);
        $singlePrice = $this->faker->randomFloat(2, 10, 1000);
        $totalPrice = $quantity * $singlePrice;

        return [
            'object' => $this->faker->sentence(),
            'quantity' => $quantity,
            'single_price' => $singlePrice,
            'total_price' => $totalPrice,
        ];
    }
}