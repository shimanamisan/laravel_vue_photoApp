<?php

namespace Tests\Feature;

use App\Photo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoDetailApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 正しい構造のJSONを返却する()
    {
        factory(Photo::class)->create();
        
        $photo = Photo::first();

        // $response = $this->json('GET', route('photo.show', ['id' => $photo->id,]));
        $response = $this->json('GET', route('photo.show', ['id' => $photo->id]));

        dd($this->json('GET', route('photo.show', ['id' => $photo->id])));

        $response->assertStatus(200)
            // GETしてきた情報のJSONのフォーマットを確かめている
            ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name,
                ],
            ]);
    }
}