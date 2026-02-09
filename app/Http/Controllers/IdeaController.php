<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use App\Services\Idea\CreateIdea;
use App\Services\Idea\UpdateIdea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $ideas = $user
            ->ideas()
            ->when(in_array($request->status, IdeaStatus::values()), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->get();

        return view('idea.index', ['ideas' => $ideas, 'statusCounts' => Idea::statusCount($user)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaRequest $request, CreateIdea $createIdea)
    {
        $createIdea->handle($request->safe()->all());

        return to_route('ideas.index')->with('success', 'New idea created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        // abort_unless($idea->user_id === Auth::id(), 403);
        // Gate::authorize('workWith', $idea);

        return view('idea.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaRequest $request, Idea $idea, UpdateIdea $updateIdea)
    {
        $updateIdea->handle($idea, $request->safe()->all());

        return back()->with('success', "Idea updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        // authorize
        abort_unless($idea->user_id === Auth::id(), 403);

        $idea->delete();

        return to_route('ideas.index')->with('success', 'Idea deleted successfully.');
    }
}
