<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Department;

class DepartmentTest extends TestCase
{
    const ADMIN_USER_ID                     = 1;
    const AGENT_USER_ID                     = 2;
    const DEPARTMENT_ADD_SUCCESS_MESSAGE    = 'Department added successfully';
    const DEPARTMENT_EDIT_SUCCESS_MESSAGE   = 'Department updated successfully';
    const DEPARTMENT_DELETE_SUCCESS_MESSAGE = 'Department deleted successfully';

    /**
     * admin can view department list page
     */
    /** @test */
    public function a_admin_can_get_departments_list_page()
    {
        $response = $this->actingAs($this->getAdminUser())->get('/department/list');

        $response->assertStatus(200);
    }

    /**
     * admin can view department add page
     */
    /** @test */
    public function a_admin_can_get_departments_add_page()
    {
        $response = $this->actingAs($this->getAdminUser())->get('/department/add');

        $response->assertStatus(200);
    }

    /**
     * admin can post department add page
     */
    /** @test */
    public function a_admin_can_post_department_add_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())->post('department/store',[
            'name' => 'Test Case Department',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::DEPARTMENT_ADD_SUCCESS_MESSAGE, $response->getContent());
    }

    /**
     * admin can post department edit page
     */
    /** @test */
    public function a_admin_can_get_departments_edit_page()
    {
        $response = $this->actingAs($this->getAdminUser())->get('/department/'.$this->getLastAddedDepartment()->id.'/edit');

        $response->assertStatus(200);
    }

    /**
     * admin can patch department edit page
     */
    /** @test */
    public function a_admin_can_patch_department_edit_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())
            ->patch('/department/'.$this->getLastAddedDepartment()->id.'/edit',[
                'name' => 'Test Case Department Edit',
            ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::DEPARTMENT_EDIT_SUCCESS_MESSAGE, $response->getContent());
    }

    /**
     * admin can delete department
     */
    /** @test  */
    public function a_admin_can_delete_department_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAdminUser())
            ->delete('/department/'.$this->getLastAddedDepartment()->id.'/delete');

        $response->assertStatus(200);
        $this->assertStringContainsString(self::DEPARTMENT_DELETE_SUCCESS_MESSAGE, $response->getContent());
    }

    protected function getAdminUser()
    {
        return User::whereId(self::ADMIN_USER_ID)->first();
    }

    protected function getLastAddedDepartment()
    {
        return Department::orderBy('id','desc')->first();
    }
}
