<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // LINE 相關欄位的預設值
            'line_id' => null,
            'line_name' => null,
            'provider' => 'email',
            'role' => 'user', // 🔑 預設角色
            'phone' => fake()->phoneNumber(),
            'is_active' => true,
            'terms_accepted' => true,
            'terms_accepted_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a LINE user.
     */
    public function lineUser(): static
    {
        return $this->state(fn(array $attributes) => [
            'line_id' => 'U' . Str::uuid(),
            'line_name' => fake()->name(),
            'provider' => 'line',
            'role' => 'user', // 🔑 LINE 使用者都是一般使用者
            'password' => Hash::make('line-user-' . time()),
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
            'provider' => 'email',
            'terms_accepted' => true,
            'terms_accepted_at' => now(),
        ]);
    }
}