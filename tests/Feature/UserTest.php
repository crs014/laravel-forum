<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\PassportTestCase;
use Mockery;

class UserTest extends PassportTestCase
{
    use RefreshDatabase;

    public function testUserLoginSuccess()
    {
        $response = $this->json('POST', '/api/user/login', [
            'email' => $this->users[0]->email,
            'password' => '12345678'
        ]);
        $response->assertStatus(200);
    }

    public function testReadSelfUserDataSuccess() 
    {
        $this->actingAs($this->users[0], 'api');
        $response = $this->json('GET', '/api/user/self');
        $response->assertStatus(200)->assertJsonFragment([
            'email' => $this->users[0]->email,
            'name' => $this->users[0]->name,
        ]);
    }
}
