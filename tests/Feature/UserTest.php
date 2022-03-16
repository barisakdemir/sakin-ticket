<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    const SUCCESS_MESSAGE = 'successfully';

    /**
     * view registration page
     *
     * @return void
     */
    /** @test  */
    public function a_guest_can_get_registration_page()
    {
        $response = $this->get('/registration');

        $response->assertStatus(200);
    }

    /**
     * post registration page
     */
    /** @test */
    public function a_guest_can_post_registration_page()
    {
        $response = $this->followingRedirects()->post('/registration', [
            'name' => 'Test Case',
            'email' => 'test.case@example.com',
            'password' => 'tEst$cASe',
        ])->assertStatus(200);

        $this->assertStringContainsString('Registered successfully', $response->getContent());
    }

    /**
     * view login page
     */
    /** @test */
    public function a_guest_can_get_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * post login page
     */
    /** @test */
    public function a_guest_can_post_login_page()
    {
        $response = $this->post('/login', [
            'email' => 'test.case@example.com',
            'password' => 'tEst$cASe',
        ])->assertStatus(302);

        $response->assertRedirect(route('dashboard'));
    }
}
