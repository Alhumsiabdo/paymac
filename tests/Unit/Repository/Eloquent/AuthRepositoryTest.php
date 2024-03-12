<?php

namespace Tests\Unit\Repository\Eloquent;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Repository\Eloquent\AuthRepository;


class AuthRepositoryTest extends TestCase
{

    use RefreshDatabase;
    public function test_register_user()
    {


        $userRepository = new AuthRepository;

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $userRepository->register($userData);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        DB::table('users')->where('email', 'john@example.com')->delete();
    }

    public function test_user_login()
    {
        // Create a user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Attempt to login with correct credentials
        $response = $this->post('login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        // Assert that the login was successful
        // $response->assertStatus(200); // Assuming successful response code for login

        // Retrieve the authenticated user from the response
        $authenticatedUser = $response->json('user');

        // Assert that the authenticated user matches the created user
        $this->assertNotNull($authenticatedUser);
        $this->assertEquals($user->id, $authenticatedUser['id']);

        // Logout the user after the test (if your API supports logout)
        $logoutResponse = $this->postJson('/api/logout');
        $logoutResponse->assertStatus(200); // Assuming successful response code for logout

        // Delete the user after the test
        $user->delete();
    }


}
