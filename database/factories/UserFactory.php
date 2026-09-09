<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
            // Akun bawaan adalah admin kelurahan; akun unit dibuat lewat
            // state eksplisit agar kolom role/unit terisi konsisten.
            'role' => User::ROLE_ADMIN,
            'unit' => null,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Akun unit untuk unit tertentu.
     */
    public function unit(string $unit = 'Unit Umum'): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $unit,
            'role' => User::ROLE_UNIT,
            'unit' => $unit,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
