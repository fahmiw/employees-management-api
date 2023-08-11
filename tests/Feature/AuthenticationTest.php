<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{   
    use RefreshDatabase;
    

    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "statusCode" => 400,
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "password_confirmation" => ["The password confirmation field is required."],
                ]
            ]);
    }

    public function testRepeatPassword()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@gmail.com",
            "password" => "demo12345",
            "password_confirmation" => "demo123452"
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "statusCode" => 400,
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => ["The password and password confirmation must match."]
                ]
            ]);
    }

    public function testSuccessfulRegistration()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@gmail.com",
            "password" => "demo12345",
            "password_confirmation" => "demo12345"
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "statusCode",
                "message",
                "data" => [
                    "name",
                    "email",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            ]);
    }

    public function testEmailTaken()
    {   
        User::factory()->create([
            'email' => 'doe@gmail.com',
        ]);

        $userData = [
            "name" => "John Doe",
            "email" => "doe@gmail.com",
            "password" => "demo12345",
            "password_confirmation" => "demo12345"
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "statusCode" => 400,
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email has already been taken."]
                ]
        ]);
    }

    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(400)
            ->assertJson([
                "statusCode" => 400,
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }

    public function testSuccessfulLogin()
    {
        User::factory()->create([
           'email' => 'sample@test.com',
           'password' => bcrypt('sample123'),
        ]);


        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "message",
                "data" => [
                    "access_token",
                ]
            ]);

        $this->assertAuthenticated();
    }

    public function testSuccessfulLogout()
    {   
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);
 
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');
        
        $this->json('GET', 'api/logout', ['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "message"
        ]);

        $this->assertGuest('api');
    }
}
