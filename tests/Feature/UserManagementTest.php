<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Tester',
            'email' => 'testuser@example.com',
            'phone_number' => '0987654321',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Debug Response
        $response->dump(); // 👈 Temporarily dump the response to see what’s going wrong

        $response->assertRedirect('/home');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function authenticated_user_can_create_user()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'phone_number' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    /** @test */
    public function authenticated_user_can_read_users()
    {
        $admin = User::factory()->create();
        $users = User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        foreach ($users as $user) {
            $response->assertSee($user->name);
        }
    }

    /** @test */
    /** @test */
    public function authenticated_user_can_update_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('users.update', $user->id), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '88888888',
            'status' => 'active',
        ]);

        $response->dump(); // 👈 Temporarily dump response to see errors

        $response->assertRedirect(route('dashboard')); // adjust if your redirect differs
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    /** @test */
    public function authenticated_user_can_delete_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user->id));
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('User Management Dashboard');
    }

    /** @test */
    public function user_can_be_deleted()
    {
        $admin = User::factory()->create();
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $userToDelete->id));
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /** @test */
    public function users_can_be_bulk_deleted()
    {
        $admin = User::factory()->create();
        $users = User::factory()->count(3)->create();

        $ids = $users->pluck('id')->toArray();

        $response = $this->actingAs($admin)->post(route('users.bulk-delete'), [
            'ids' => json_encode($ids),
        ]);

        $response->assertRedirect(route('dashboard'));

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('users', ['id' => $id]);
        }
    }
}
