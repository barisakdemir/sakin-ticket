<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;

class TicketTest extends TestCase
{
    const AGENT_USER_ID = 2;
    const CUSTOMER_USER_ID = 4;
    const TICKET_ID = 1;
    const TICKET_SEND_SUCCESS_MESSAGE = 'Message sent successfully';
    const TICKET_ADD_SUCCESS_MESSAGE = 'Ticket added successfully';
    const TICKET_MESSAGE_ADD_SUCCESS_MESSAGE = 'Message sent successfully';

    /**
     * agent can view ticket list page
     */
    /** @test */
    public function a_agent_can_get_ticket_list_page()
    {
        $response = $this->actingAs($this->getAgentUser())->get('/agent/ticket/list');

        $response->assertStatus(200);
    }

    /**
     * agent can view ticket page
     */
    /** @test */
    public function a_agent_can_get_ticket_page()
    {
        $response = $this->actingAs($this->getAgentUser())->get('/agent/ticket/'.self::TICKET_ID.'/view');

        $response->assertStatus(200);
    }

    /**
     * admin can post ticket massage page
     */
    /** @test */
    public function a_admin_can_post_department_add_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getAgentUser())
            ->post('agent/ticket/'.self::TICKET_ID.'/message/store',[
            'message' => 'Agent send message test case',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::TICKET_SEND_SUCCESS_MESSAGE, $response->getContent());
    }

    /**
     * customer can view ticket list page
     */
    /** @test */
    public function a_customer_can_get_ticket_list_page()
    {
        $response = $this->actingAs($this->getCustomerUser())->get('/customer/ticket/list');

        $response->assertStatus(200);
    }

    /**
     * customer can view ticket add page
     */
    /** @test */
    public function a_customer_can_get_ticket_add_page()
    {
        $response = $this->actingAs($this->getCustomerUser())->get('/customer/ticket/add');

        $response->assertStatus(200);
    }

    /**
     * customer can post ticket page
     */
    /** @test */
    public function a_admin_can_post_ticket_add_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getCustomerUser())
            ->post('/customer/ticker/store',[
                'title'         => 'Test Case Title',
                'department'    => 1,
                'importance'    => 1,
                'message'       => 'Test Case Message',
            ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::TICKET_ADD_SUCCESS_MESSAGE, $response->getContent());
    }

    /**
     * customer can view ticket view page
     */
    /** @test */
    public function a_customer_can_get_ticket_view_page()
    {
        $response = $this->actingAs($this->getCustomerUser())
            ->get('/customer/ticket/'.$this->getLastAddedTicket()->id.'/view');

        $response->assertStatus(200);
    }

    /**
     * customer can post ticket message page
     */
    /** @test */
    public function a_admin_can_post_ticket_message_add_page()
    {
        $response = $this->followingRedirects()->actingAs($this->getCustomerUser())
            ->post('/customer/ticket/'.$this->getLastAddedTicket()->id.'/message/store',[
                'message'       => 'Test Case Message Reply',
            ]);

        $response->assertStatus(200);
        $this->assertStringContainsString(self::TICKET_MESSAGE_ADD_SUCCESS_MESSAGE, $response->getContent());
    }

    protected function getAgentUser()
    {
        return User::whereId(self::AGENT_USER_ID)->first();
    }

    protected function getCustomerUser()
    {
        return User::whereId(self::CUSTOMER_USER_ID)->first();
    }

    protected function getLastAddedTicket()
    {
        return Ticket::orderBy('id','desc')->first();
    }
}
