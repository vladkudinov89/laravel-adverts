<?php

namespace Tests\Feature\Auth;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    public function testBasicTest(): void
    {
        $response = $this->get('/register');

        $response
            ->assertStatus(200)
            ->assertSee('Register');
    }

    public function testErrors(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' , 'email' , 'password']);
    }

    public function testSuccess(): void
    {
        $user = factory(User::class)->make();

        $response = $this->post('/register' , [
           'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Check your email and click on the link to verify.');
    }

    public function testVerifyError(): void
    {
        $response = $this->get('/verify/'. Str::uuid());

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'Sorry, your link cannot be verified.');
    }

    public function testVerifyOk(): void
    {
        $user = factory(User::class)->create([
            'status' => User::STATUS_WAIT,
            'verify_token' => Str::uuid()
        ]);

        $response = $this->get('/verify/'. $user->verify_token);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success' , 'Your e-mail  is verified. You can now login.');
    }
}
