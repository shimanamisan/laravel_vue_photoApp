<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterApiTest extends TestCase
{
    // このコメントも意味を持っている
    /**
    * @test
    */

    use RefreshDatabase;

    public function 新しいユーザーを作成して返却する()
    {
        $data = [
            'name' => 'testUser',
            'email' => 'test@example.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response->assertStatus(201)->assertJson( ['name' => $user->name ]);
    }
}
