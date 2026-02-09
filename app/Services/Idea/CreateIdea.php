<?php

declare(strict_types=1);

namespace App\Services\Idea;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateIdea
{
    public function __construct(#[CurrentUser] protected User $user) {}

    public function handle(array $attributes): void
    {
        // /** @var User $user */
        // $user ??= Auth::user();
        $data = collect($attributes)
            ->only(['title', 'description', 'status', 'links'])
            ->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        $steps = collect($attributes['steps'] ?? [])->map(fn ($step) => ['description' => $step])->toArray();
        DB::transaction(function () use ($data, $steps): void {
            $idea = $this->user->ideas()->create($data);
            if (! empty($steps)) {
                $idea->steps()->createMany($steps);
            }
        });
    }
}
