<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>
            
            <x-card 
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                class="mt-10 cursor-pointer h-32 w-full text-left" 
                is="button"
                type="button"
                data-test="create-idea-button"
            >
                <p>What's the idea?</p>
            </x-card>
        </header>

        <div>
            <a href="{{ route('ideas.index') }}" class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}">
                All
                <span class="text-xs pl-3">{{ $statusCounts->get('all') }}</span>
            </a>
            @foreach (App\IdeaStatus::cases() as $status)
                <a
                    href="{{ route('ideas.index', ['status' => $status->value]) }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}"
                >
                    {{ $status->label() }} <span class="text-xs pl-3">{{ $statusCounts->get($status->value) }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('ideas.show', $idea) }}">
                        @if ($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-auto max-h-72 object-cover">
                            </div>
                        @endif

                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <div class="mt-1">
                            <x-idea.status-label status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-idea.status-label>
                        </div>

                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No ideas at this time.</p>
                    </x-card>
                @endforelse
            </div>
        </div>

        {{-- Modal --}}
        <x-modal name="create-idea" title="New Idea">
            <form
                x-data="{status: 'pending', newLink: '', links: [], newStep: '', steps: []}"
                method="POST"
                action="{{ route('ideas.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="space-y-6">
                    <x-form.field label="Title" name="title" placeholder="Enter an idea for your title" autofocus required />
                    <x-form.field label="Description" name="description" type="textarea" placeholder="Describe your idea..." />
                    
                    <div class="space-y-2">
                        <input type="file" name="image" accept="image/*" >
                        <x-form.error name="image" />
                    </div>

                    <div class="space-y-3">
                        <label for="status" class="label">Status</label>

                        <div class="flex gap-x-3">
                            @foreach (App\IdeaStatus::cases() as $status)
                                <button data-test="button-status-{{ $status->value }}" type="button" @click="status = @js($status->value)" class="btn flex-1 h-10" :class="{'btn-outlined': @js($status->value) !== status}">{{ $status->label() }}</button>
                            @endforeach

                            <input type="hidden" name="status" :value="status" />
                        </div>

                        <x-form.error name="status" />
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Actionable Steps</legend>

                            <template x-for="(step, index) in steps" :key="step">
                                <div class="flex gap-x-2 items-center">
                                    <label for="" class="sr-only">Step</label>
                                    <input class="input" name="steps[]" x-model="steps[index]" readonly />
                                    <button @click="steps.splice(index, 1)" type="button" aria-label="Remove step" class="form-muted-icon">
                                        <x-icons.close  />
                                    </button>
                                </div>
                            </template>

                            <div class="flex gap-x-2 items-center" id="step-field">
                                <input
                                    x-model="newStep"
                                    id="new-step"
                                    data-test="new-step"
                                    class="input flex-1"
                                    spellcheck="false"
                                    x-ref="newStepInput"
                                >
                                <button 
                                    type="button" 
                                    data-test="submit-new-step-button"
                                    class="rotate-45 form-muted-icon" 
                                    @click="if($refs.newStepInput.checkValidity()) { steps.push(newStep.trim()); newStep = '' }"
                                    :disabled="newStep.trim().length === 0"    
                                    aria-label="Add a new step"
                                >
                                    <x-icons.close />
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>

                            <template x-for="(link, index) in links" :key="link">
                                <div class="flex gap-x-2 items-center">
                                    <label for="" class="sr-only">Link</label>
                                    <input class="input" name="links[]" x-model="links[index]" />
                                    <button @click="links.splice(index, 1)" type="button" aria-label="Remove link" class="form-muted-icon">
                                        <x-icons.close  />
                                    </button>
                                </div>
                            </template>

                            <div class="flex gap-x-2 items-center" id="link-field">
                                <input
                                    x-model="newLink"
                                    type="url"
                                    id="new-link"
                                    data-test="new-link"
                                    placeholder="http://example.com"
                                    autocomplete="url"
                                    class="input flex-1"
                                    spellcheck="false"
                                    x-ref="newLinkInput"
                                >
                                <button 
                                    type="button" 
                                    data-test="submit-new-link-button"
                                    class="rotate-45 form-muted-icon" 
                                    @click="if($refs.newLinkInput.checkValidity()) { links.push(newLink.trim()); newLink = '' }"
                                    :disabled="newLink.trim().length === 0"    
                                    aria-label="Add a new link"
                                >
                                    <x-icons.close />
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div class="flex justify-end gap-x-5">
                        <button type="button" class="cursor-pointer" @click="$dispatch('close-modal')">Cancel</button>
                        <button type="submit" class="btn">Create</button>
                    </div>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>