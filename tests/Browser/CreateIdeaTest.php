<?php

use App\Models\Idea;
use App\Models\User;

it('creates a new idea', function() {
  $this->actingAs($user = User::factory()->create());

  visit('/ideas')
    ->click('@create-idea-button')
    ->fill('title', 'Test title')
    ->fill('description', 'Test description')
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
    ]);
    
    expect(Idea::count())->toBe(1);

    // ->debug();
});