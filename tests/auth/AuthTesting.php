<?php
namespace Tests\auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTesting extends TestCase
{
    public function test_user_can_view_a_login_form()
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        $response->assertViewIs('frontend.layouts.partials._authmodel');
    }
}