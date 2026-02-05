<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('logs in a user', function() {
    $user = User::factory()->create(['password' => 'P@ssw0rd']);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'P@ssw0rd')
        ->click("@login-button")
        ->assertPathIs('/');

    $this->assertAuthenticated();
});

it('logs out a user', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    visit('/')->click('@logout-button');

    $this->assertGuest();
});
