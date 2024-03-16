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
}
