<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    const ADMIN_USER_ID                 = 1;
    const AGENT_USER_ID                 = 2;
    const USER_ADD_SUCCESS_MESSAGE      = 'User added successfully';
    const USER_EDIT_SUCCESS_MESSAGE     = 'User updated successfully';
    const USER_DELETE_SUCCESS_MESSAGE   = 'User deleted successfully';

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
            'name'      => 'Test Case',
            'email'     => 'test.case@example.com',
            'password'  => 'tEst$cASe',
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

    /**
     * admin can view users list page
     */
    /** @test */
    public function a_admin_can_get_users_list_page()
    {
        $response = $this->actingAs($this->getAdminUser())
            ->get('/user/list');

        $response->assertStatus(200);
        $this->assertStringContainsString($this->getAdminUser()->email, $response->getContent());
    }

    /**
     * admin can view user add page
     */
    /** @test */
    public function a_admin_can_get_user_add_page()
    {
        $response = $this->actingAs($this->getAdminUser())
            ->get('/user/add');

        $response->assertStatus(200);
    }

    /**
     * admin can post user add page
     */
    /** @test */
    public function a_admin_can_post_user_add_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())->post('/user/store',[
            'name'      => 'Test Case Admin Add Agent',
            'email'     => 'test.case.admin.add.agent@test.com',
            'password'  => 'password',
            'type'      => 'agent',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::USER_ADD_SUCCESS_MESSAGE, $response->getContent());
    }

    /**
     * admin can view user edit page
     */
    /** @test */
    public function a_admin_can_get_user_edit_page()
    {
        $response = $this->actingAs($this->getAdminUser())->get('/user/'.self::AGENT_USER_ID.'/edit');

        $response->assertStatus(200);
    }

    /**
     * admin can patch user edit page
     */
    /** @test */
    public function a_admin_can_patch_user_edit_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())
            ->patch('/user/'.self::AGENT_USER_ID.'/edit',[
            'name'      => 'Test Case Admin Edit Agent',
            'email'     => 'test.case.admin.edit.agent@test.com',
            'password'  => 'password',
            'type'      => 'agent',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::USER_EDIT_SUCCESS_MESSAGE, $response->getContent());
    }

    /**
     * admin can delete user
     */
    /** @test  */
    public function a_admin_can_delete_user_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())
            ->delete('/user/'.$this->getLastAddedUser()->id.'/delete');

        $response->assertStatus(200);
        $this->assertStringContainsString(self::USER_DELETE_SUCCESS_MESSAGE, $response->getContent());
    }

    protected function getAdminUser()
    {
        return User::whereId(self::ADMIN_USER_ID)->first();
    }

    protected function getLastAddedUser()
    {
        return User::orderBy('id','desc')->first();
    }
}
