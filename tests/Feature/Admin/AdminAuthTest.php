<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class AdminAuthTest extends TestCase
{
    public function test_admin_can_login()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin'
        ]);

        $response->assertRedirect('/dashboard');
        
        $this->assertAuthenticated();
        $this->assertTrue(Auth::user()->status === 1);
    }

    // public function test_non_admin_cannot_access_admin_pages()
    // {
    //     $user = User::where('status', 0)->first();
        
    //     if (!$user) {
    //         $this->markTestSkipped('Tidak ada user biasa di database.');
    //     }

    //     $this->actingAs($user);

    //     $response = $this->get('/manage/opd');
    //     $response->assertStatus(403);
    // }

    public function test_guest_cannot_access_admin_pages()
    {
        $response = $this->get('/manage/opd');
        $response->assertRedirect('/login');
    }

    public function test_wrong_admin_credentials_cannot_login()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
} 