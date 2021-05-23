<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    const API_REGISTER_URL = 'api/register';
    const API_LOGIN_URL = 'api/login';

    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', self::API_REGISTER_URL, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."]
                ]
            ]);
    }

    public function testSuccessfulRegistration()
    {
        $userData = [
            "name" => "Pankaj",
            "email" => "pankaj@gmail.com",
            "password" => "pankaj123",
            "password_confirmation" => "pankaj123"
        ];

        $this->json('POST', self::API_REGISTER_URL, $userData,['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "user" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                "access_token"
            ]);
    }

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            "email" => "pankaj@gmail.com",
            "password" => bcrypt("test123")
        ]);

        $loginData =  ["email" => "pankaj@gmail.com", "password" => "test123"];

        $this->json('POST', self::API_LOGIN_URL, $loginData, ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJsonStructure([
                "user" => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ],
                "access_token"
             ]);

        $this->assertAuthenticated();
    }

    public function testLoginFailure()
    {
        $user = User::factory()->create([
            "email" => "pankaj@gmail.com",
            "password" => bcrypt("test")
        ]);

        $loginData =  ["email" => "pankaj@gmail.com", "password" => "test123"];

        $this->json('POST', self::API_LOGIN_URL, $loginData, ['Accept' => 'application/json'])
             ->assertStatus(401);
    }
}
