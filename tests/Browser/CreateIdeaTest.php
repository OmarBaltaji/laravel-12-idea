<?php

use App\Models\Idea;
use App\Models\User;

it('creates a new idea', function() {
  $this->actingAs($user = User::factory()->create());

  visit('/ideas')
    ->click('@create-idea-button')
    ->fill('title', 'Test title')
    ->fill('description', 'Test description')
    ->fill('@new-link', 'https://laracasts.com')
    ->click('@submit-new-link-button')
    ->fill('@new-link', 'https://laravel.com')
    ->click('@submit-new-link-button')
    ->click('@button-status-in_progress')
    ->click('Create')
    ->assertPathIs('/ideas');


    // expect(Idea::first())->toMatchArray([
    //   'title' => 'Test title',
    //   'description' => 'Test description',
    //   'status' => 'in_progress',
    // ]);
    expect($user->ideas()->first())->toMatchArray([
      'title' => 'Test title',
      'description' => 'Test description',
      'status' => 'in_progress',
      'links' => ['https://laracasts.com', 'https://laravel.com']
    ]);
    
    expect(Idea::count())->toBe(1);

    // ->debug();
});