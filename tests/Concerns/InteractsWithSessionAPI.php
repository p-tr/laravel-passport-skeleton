<?php

namespace Tests\Concerns;

use Tests\TestCase as BaseTestCase;

use App\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait InteractsWithSessionAPI
{
    private $user = null;
    private $session = null;
    private $secret = null;

    private function createTestUser()
    {
        if(!$this->secret) {
            $this->secret = md5(env('APP_NAME', '-') . env('APP_KEY', '-') . now());
        }

        if(!$this->user) {
            $this->user = new User;
            $this->user->email = $this->secret . '@example.com';
            $this->user->name = $this->secret;
            $this->user->password = Hash::make($this->secret);
            $this->user->remember_token = Str::random(10);
            $this->user->email_verified_at = now();
            $this->user->save();
        }
    }

    private function deleteTestUser()
    {
        if($this->user) {
            $this->user->delete();
            $this->user = null;
        }
    }

    private function login()
    {
        $this->createTestUser();

        if(!$this->session) {
            $response = $this->json('POST', '/api/token', [
                'email' => $this->user->email,
                'password' => $this->secret,
            ]);

            $response->assertStatus(200);

            $this->session = json_decode($response->content());
        }
    }

    private function logout()
    {
        if($this->session) {
            $response = $this->json('DELETE', '/api/token', [], [
                'Authorization' => 'Bearer ' . $this->session->access_token,
            ]);

            $response->assertStatus(204);

            $this->session = null;
        }

        $this->deleteTestUser();
    }
}
