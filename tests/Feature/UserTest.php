<?php

namespace Tests\Feature;

use Tests\TestCase;

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
                "username" => [
                    "Username has already been taken"
                ]
            ]
        ]);
    }
}
