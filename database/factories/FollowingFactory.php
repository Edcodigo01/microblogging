<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Following>
 */
class FollowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = 1;
        $max = 20;
        $user_id = rand($min, $max);

        $following_id = $this->randomExcluding($min, $max, $user_id);

        return [
            'user_id' => $user_id,
            'following_id' => $following_id
        ];
    }

    public function randomExcluding($min, $max, $exclude)
    {
        do {
            $random = rand($min, $max); // También puedes usar mt_rand($min, $max)
        } while ($random == $exclude); // Repite si el número es el excluido

        return $random;
    }

}
