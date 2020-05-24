<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\PassportTestCase;
use App\Models\User;
use App\Models\Post;
use Mockery;

class PostTest extends PassportTestCase
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
        $mock->shouldReceive('authorize')->with($type, Post::class)->once()->andReturn(true);
        $this->app->instance('Illuminate\Contracts\Auth\Access\Gate', $mock);
        $this->actingAs($this->users[0], 'api');
    }

    public function testFetchPostsSuccess() 
    {
        $this->_setAuthorizeTestGate('index');
        $response = $this->json('GET', '/api/post');
        $response->assertStatus(200);
    }

    public function testCreatePostSuccess() 
    {
        $this->_setAuthorizeTestGate('store');
        $response = $this->json('POST', '/api/post', [
            'text' => 'hi this is first post'
        ]);
        $response->assertStatus(201)->assertJsonFragment([
            'text' => 'hi this is first post'
        ]);
    }

    public function testFetchPostSuccess() 
    {
        $this->testCreatePostSuccess();
        $this->_setAuthorizeTestGate('show');
        $response = $this->json('GET', '/api/post/1');
        $response->assertStatus(200)->assertJsonFragment([
            'text' => 'hi this is first post'
        ]);
    }

    public function testFetchPostNotFound() 
    {
        $this->_setAuthorizeTestGate('show');
        $response = $this->json('GET', '/api/post/1');
        $response->assertStatus(404);
    }

    public function testUpdatePostSuccess() 
    {
        $this->testCreatePostSuccess();
        $this->_setAuthorizeTestGate('update');
        $response = $this->json('PATCH', '/api/post/1', [
            'text' => 'post is updated'
        ]);
        $response->assertStatus(200)->assertJsonFragment([
            'text' => 'post is updated'
        ]);
    }

    public function testUpdatePostNotFound() 
    {
        $this->_setAuthorizeTestGate('update');
        $response = $this->json('PATCH', '/api/post/1', [
            'text' => 'post is updated'
        ]);
        $response->assertStatus(404);
    }

    public function testDeletePostSuccess() 
    {
        $this->testCreatePostSuccess();
        $this->_setAuthorizeTestGate('destroy');
        $response = $this->json('DELETE', '/api/post/1');
        $response->assertStatus(200);
    }

    public function testDeletePostNotFound() 
    {
        $this->_setAuthorizeTestGate('destroy');
        $response = $this->json('DELETE', '/api/post/1');
        $response->assertStatus(404);
    }

    public function testCommentPostSuccess() 
    {
        $this->testCreatePostSuccess();
        $this->_setAuthorizeTestGate('comment');
        $response = $this->json('POST', '/api/post/1/comment', [
            'text' => 'this is comment text'
        ]);
        $response->assertStatus(201)->assertJsonFragment([
            'text' => 'this is comment text'
        ]);
    }
}
