<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $email = fake()->email;
        $phoneNumber = fake()->e164PhoneNumber;
        $displayName = fake()->name;


        $auth = app('firebase.auth');

        $userProperties = [
            'email' => $email,
            'emailVerified' => false,
            'phoneNumber' => $phoneNumber,
            'password' => 'zaq1@WSX',
            'displayName' => $displayName,
            'photoUrl' => 'http://www.example.com/12345678/photo.png',
            'disabled' => false,
        ];

        $createdUser = $auth->createUser($userProperties);
        $user = $auth->getUserByEmail($email);


        return [
            'firebase_uid' => $user->uid,
            'name' => $user->displayName,
            'email' => $user->email,
            'email_verified_at' => null,
            'remember_token' => null,
            'popularity' => 0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
