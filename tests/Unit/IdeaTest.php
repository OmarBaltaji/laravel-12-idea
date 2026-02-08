<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

// To prove that Idea has a relation user()
test('it belongs to a user', function () {
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(User::class);
});

test('it can have steps', function () {
    $idea = Idea::factory()->create();

    // expect($idea->steps)->toBeInstanceOf(Collection::class);
    expect($idea->steps)->toBeEmpty();

    $idea->steps()->create([
        'description' => 'Do the thing',
    ]);

    expect($idea->fresh()->steps)->toHaveCount(1);
});
