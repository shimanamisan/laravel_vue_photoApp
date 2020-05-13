<?php

namespace Tests\Feature;

use App\User; // ★
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setUP() :void // 戻り値の型を指定
    {

        parent::setUp();

        // テストユーザー作成
        $this->user = factory(User::class)->create();
    }

    /**
    * @test
    */

    public function ログイン中のユーザーを返却する()
    {
        $response = $this->json('GET', route('user'));

        $response->assertStatus(200);

        // ログインしていない場合、Auth::userはnullを返すが、APIは空文字を返す
        // HTTPレスポンス変換時にnullは空文字に変換されるため
        // HTTP通信はテキストのやり取りなので、nullやfalseなどのプログラミング言語的な表現はされないから
        $this->assertEquals("", $response->content());
    }
}
