<?php

declare(strict_types=1);

use App\Models\User;

it('logs in a user', function () {
    $user = User::factory()->create(['password' => 'P@ssw0rd']);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'P@ssw0rd')
        ->click('@login-button')
        ->assertPathIs('/ideas');
    // ->assertRoute('ideas.index')

    $this->assertAuthenticated();
});

it('logs out a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // to_route('ideas.index')
    visit('/')->click('@logout-button');

    $this->assertGuest();
});
