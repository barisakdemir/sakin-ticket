<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DepartmentAgentTest extends TestCase
{
    const ADMIN_USER_ID = 1;
    const AGENT_USER_ID = 2;
    const DEPARTMENT_ID = 2;
    const DEPARTMENT_AGENT_ADD_SUCCESS_MESSAGE = 'Agent added successfully';
    const DEPARTMENT_AGENT_DELETE_SUCCESS_MESSAGE = 'Agent deleted successfully';

    /**
     * admin can view department agents list page
     */
    /** @test */
    public function a_admin_can_get_department_agents_list_page()
    {
        $response = $this->actingAs($this->getAdminUser())->get('department/'.self::DEPARTMENT_ID.'/agent/list');

        $response->assertStatus(200);
    }

    /**
     * admin can post department agent add page
     */
    /** @test */
    public function a_admin_can_post_department_agent_add_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())
            ->post('/department/'.self::DEPARTMENT_ID.'/agent/store',[
                'user_id' => self::AGENT_USER_ID,
            ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::DEPARTMENT_AGENT_ADD_SUCCESS_MESSAGE, $response->getContent());
    }

    /**
     * admin can delete department agent
     */
    /** @test  */
    public function a_admin_can_delete_department_agent_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())
            ->delete('/department/'.self::DEPARTMENT_ID.'/agent/'.self::AGENT_USER_ID.'/delete');

        $response->assertStatus(200);
        $this->assertStringContainsString(self::DEPARTMENT_AGENT_DELETE_SUCCESS_MESSAGE, $response->getContent());
    }

    protected function getAdminUser()
    {
        return User::whereId(self::ADMIN_USER_ID)->first();
    }
}
