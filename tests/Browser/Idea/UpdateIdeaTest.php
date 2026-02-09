<?php

use App\Models\Idea;
use App\Models\User;

it('shows the initial title', function () {
    $this->actingAs($user = User::factory()->create());

    $idea = Idea::factory()->for($user)->create();

    visit(route('ideas.show', $idea))
        ->click('@edit-idea-button')
        ->assertValue('title', $idea->title)
        ->assertValue('description', $idea->description)
        ->assertValue('status', $idea->status->value);
});

it('edits an existing idea', function () {
    $this->actingAs($user = User::factory()->create());

    $idea = Idea::factory()->for($user)->create();

    visit(route('ideas.show', $idea))
        ->click('@edit-idea-button')
        ->fill('title', 'Test title')
        ->fill('description', 'Test description')
        ->fill('@new-link', 'https://laracasts.com')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://laravel.com')
        ->click('@submit-new-link-button')
        ->fill('@new-step', 'step 1')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'step 2')
        ->click('@submit-new-step-button')
        ->click('@button-status-in_progress')
        ->click('Update')
        ->assertRoute('ideas.show', [$idea]);

    expect($idea = $user->ideas()->first())->toMatchArray([
        'title' => 'Test title',
        'description' => 'Test description',
        'status' => 'in_progress',
        'links' => [$idea->links[0], 'https://laracasts.com', 'https://laravel.com'],
    ]);

    expect(Idea::count())->toBe(1);

    expect($idea->steps)->toHaveCount(2);
});
