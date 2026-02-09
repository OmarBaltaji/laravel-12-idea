<?php

declare(strict_types=1);

namespace App\Services\Idea;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class UpdateIdea
{
    public function handle(Idea $idea, array $attributes): void
    {
        $data = collect($attributes)
            ->only(['title', 'description', 'status', 'links'])
            ->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');

            if ($data['image_path'] === false) {
                throw new \RuntimeException('Failed to store uploaded image.');
            }
        }

        DB::transaction(function () use ($data, $idea, $attributes): void {
            $idea->update($data);

            $idea->steps()->delete();
            $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
