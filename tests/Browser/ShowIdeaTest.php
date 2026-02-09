<?php

use App\Models\Idea;
use App\Models\User;

it('requires authentication', function () {
  $idea = Idea::factory()->create();

  $this->get(route('ideas.show', $idea))->assertRedirectToRoute('login');
});

it('disallows accessing an idea you did not create', function() {
  $user = User::factory()->create();

  $this->actingAs($user);

  // Idea does not have a user
  $idea = Idea::factory()->create();

  $this->get(route('ideas.show', $idea))->assertForbidden();
});