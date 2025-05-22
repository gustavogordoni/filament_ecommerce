<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'grand_total' => 0,
            'payment_method' => $this->faker->randomElement(['stripe', 'cod']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
            'status' => $this->faker->randomElement(['new', 'processing', 'shipped', 'delivered', 'cancelled']),
            'currency' => $this->faker->randomElement(['brl', 'usd', 'eur']),
            'shipping_amount' => 0,
            'shipping_method' => $this->faker->randomElement(['correios', 'fedex', 'ups']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::inRandomOrder()->limit(rand(1, 5))->get();

            $grandTotal = 0;

            foreach ($products as $product) {
                $quantity = rand(1, 3);
                $unitAmount = $product->price;
                $totalAmount = $unitAmount * $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_amount' => $unitAmount,
                    'total_amount' => $totalAmount,
                ]);

                $grandTotal += $totalAmount;
            }

            $order->update(['grand_total' => $grandTotal]);

            $order->address()->create([
                'first_name' => $order->user->name,
                'last_name' => '',
                'phone' => fake()->phoneNumber(),
                'street_address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'zip_code' => fake()->postcode(),
            ]);
        });
    }
}
