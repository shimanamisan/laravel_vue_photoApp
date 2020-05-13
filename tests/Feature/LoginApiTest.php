<?php

namespace Tests\Feature;

use App\User; // ★
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     use RefreshDatabase;

     public function setUp() :void // 戻り値の型を指定
     {
         parent::setUp();

         // テストユーザー作成
         $this->user = factory(User::class)->create();
     }

    // このコメントも意味を持っている
    /**
    * @test
    */
    public function 登録済みのユーザーを認証して返却する()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)->assertJson( ['name' => $this->user->name] );

        $this->assertAuthenticatedAs($this->user);
    }
}
