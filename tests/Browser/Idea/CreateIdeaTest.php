<?php

use App\Models\Idea;
use App\Models\User;

// use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Storage;

it('creates a new idea', function () {
    $this->actingAs($user = User::factory()->create());

    // Storage::fake('public');
    // $file = UploadedFile::fake()->image('image.png');
    // $filePath = $file->getRealPath();

    visit('/ideas')
        ->click('@create-idea-button')
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
      // ->attach('@image-field', $filePath)
        ->click('Create')
        ->assertPathIs('/ideas');

    // expect(Idea::first())->toMatchArray([
    //   'title' => 'Test title',
    //   'description' => 'Test description',
    //   'status' => 'in_progress',
    // ]);
    expect($idea = $user->ideas()->first())->toMatchArray([
        'title' => 'Test title',
        'description' => 'Test description',
        'status' => 'in_progress',
        'links' => ['https://laracasts.com', 'https://laravel.com'],
    ]);

    expect(Idea::count())->toBe(1);

    expect($idea->steps)->toHaveCount(2);
    // ->debug();
});
