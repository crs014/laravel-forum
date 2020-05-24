<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Follow;
use Tests\PassportTestCase;
use Mockery;

class FollowTest extends PassportTestCase
{
    use RefreshDatabase;
    
    /**
     * @description: Set mock authorize gate for testing or skip the authorize gate
     * @author: Cristono Wijaya
     * @param: string $type
    */
    private function _setAuthorizeTestGate($type)
    {
        $mock = Mockery::mock('Illuminate\Contracts\Auth\Access\Gate');
        $mock->shouldReceive('authorize')->with($type, Follow::class)->once()->andReturn(true);
        $this->app->instance('Illuminate\Contracts\Auth\Access\Gate', $mock);
        $this->actingAs($this->users[0], 'api');
    }

    public function testFetchFollowersSuccess() 
    {
        $this->_setAuthorizeTestGate('followers');
        $response = $this->json('GET', '/api/follow/followers');
        $response->assertStatus(200);
    }

    public function testFetchFollowingSuccess() 
    {
        $this->_setAuthorizeTestGate('following');
        $response = $this->json('GET', '/api/follow/following');
        $response->assertStatus(200);
    }

    public function testFollowAnotherUserSuccess() 
    {
        $this->_setAuthorizeTestGate('following_user');
        $response = $this->json('POST', '/api/follow/2');
        $response->assertStatus(201);
    }

    public function testUnfollowAnotherUserSuccess() 
    {
        $this->testFollowAnotherUserSuccess();
        $this->_setAuthorizeTestGate('destroy');
        $response = $this->json('DELETE', '/api/follow/2');
        $response->assertStatus(200);
    }
}
