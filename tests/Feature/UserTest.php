<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post("/api/auth/register", [
            "username" => "dhirojap",
            "password" => "password123",
            "name" => "Dhiro Jap"
        ])->assertStatus(201)
        ->assertJson([
            "data" => [
                "username" => "dhirojap",
                "name" => "Dhiro Jap"
            ]
        ]);
    }

    public function testRegisterFailed()
    {
        $this->post("/api/auth/register", [
            "username" => "",
            "password" => "",
            "name" => ""
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "username" => [
                    "The username field is required."
                ],
                "password" => [
                    "The password field is required."
                ],
                "name" => [
                    "The name field is required."
                ]
            ]
        ]);
    }

    public function testRegisterUsernameExists()
    {
        $this->testRegisterSuccess();
        $this->post("/api/auth/register", [
            "username" => "dhirojap",
            "password" => "password123",
            "name" => "Dhiro Jap"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "username" => "Username has already been taken"
            ]
        ]);
    }

    public function testLoginSuccess()
    {
        $this->seed(UserSeeder::class);
        $this->post("/api/auth/login", [
            "username" => "dhirojap",
            "password" => "password123"
        ])->assertStatus(200)
        ->assertJson([
            "data"=> [
                "username" => "dhirojap",
                "name" => "Dhiro Jap"
            ]
        ]);

        $user = User::where("username","dhirojap")->first();
        assertNotNull($user->token);
    }

    public function testLoginFailed()
    {
        $this->post("/api/auth/login", [
            "username" => "dhirojaps",
            "password" => "password123s"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => "Username or password is incorrect"
            ]
        ]);
    }

    public function testLoginIncorrectPassword()
    {
        $this->seed(UserSeeder::class);
        $this->post("/api/auth/login", [
            "username" => "dhirojap",
            "password" => "password123sss"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => "Username or password is incorrect"
            ]
        ]);
    }

    public function testGetUser()
    {
        $this->seed(UserSeeder::class);
        $this->get("api/auth/users/current", [
            "Authorization" => "token"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "dhirojap",
                "name"=> "Dhiro Jap"
            ]
        ]);
    }

    public function testGetUserFailed()
    {
        $this->seed(UserSeeder::class);
        $this->get("api/auth/users/current")
        ->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => "Unauthorized"
            ]
        ]);
    }

    public function testGetUserInvalidToken()
    {
        $this->seed(UserSeeder::class);
        $this->get("api/auth/users/current", [
            "Authorization" => "wrongtoken"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => "Unauthorized"
            ]
        ]);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where("username", "dhirojap")->first();

        $this->patch("api/auth/users/current", [
            "password" => "newpassword"
        ],
        [
            "Authorization" => "token"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "dhirojap",
                "name" => "Dhiro Jap"
            ]
        ]);

        $newUser = User::where("username", "dhirojap")->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where("username", "dhirojap")->first();

        $this->patch("api/auth/users/current", [
            "name" => "TestingNew"
        ],
        [
            "Authorization" => "token"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "dhirojap",
                "name" => "TestingNew"
            ]
        ]);

        $newUser = User::where("username", "dhirojap")->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
    }
}
