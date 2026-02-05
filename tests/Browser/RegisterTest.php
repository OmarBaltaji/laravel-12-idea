<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;

it('registers a user', function() {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john.doe@mail.com')
        ->fill('password', 'P@sww0rd')
        ->click("Create Account")
        ->assertPathIs('/');

    $this->assertAuthenticated();

    // $this->assertDatabaseHas('users', [
    //     ''
    // ]);
    expect(Auth::user())->toMatchArray([
        'name' => "John Doe",
        'email' => "john.doe@mail.com",
    ]);
});

it('requires a valid email', function() {
    visit('/register')
        ->fill('name', 'Test User')
        ->fill('email', 'testuser')
        ->fill('password', 'P@ssw0rd')
        ->click('Create Account')
        ->assertPathIs('/register');
});