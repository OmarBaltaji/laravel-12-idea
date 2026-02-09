@props(['idea' => new App\Models\Idea()])

<x-modal name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}" title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}">
  <form
      x-data="{
        status: @js(old('status', $idea->status->value)),
        newLink: '',
        links: @js(old('links', $idea->links ?? [])),
        newStep: '',
        steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'completed'])))
      }"
      method="POST"
      action="{{ $idea->exists ? route('ideas.update', $idea) : route('ideas.store') }}"
      enctype="multipart/form-data"
      {{-- multipart/form-data doesn't work on pest browser --}}
  >
      @csrf
      @if ($idea->exists)      
        @method("PATCH")
      @endif

      <div class="space-y-6">
          <x-form.field label="Title" :value="$idea->title" name="title" placeholder="Enter an idea for your title" autofocus required />
          <x-form.field label="Description" :value="$idea->description" name="description" type="textarea" placeholder="Describe your idea..." />
          
          <div class="space-y-2">
              <label for="image" class="label">Featured Image</label>

              @if ($idea->image_path)
                <div class="space-y-2">
                  <img class="w-full h-48 object-cover rounded-lg" src="{{ asset('storage/' . $idea->image_path) }}" alt="Featured image" >
                  <button form="delete-image-form" type="submit" class="btn btn-outlined h-10 w-full flex justify-center">
                    Remove image
                    <x-icons.trash />
                  </button>
                </div>
              @endif
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

                  <template x-for="(step, index) in steps" :key="step.id || index">
                      <div class="flex gap-x-2 items-center">
                          <label for="" class="sr-only">Step</label>
                          <input class="input" :name="`steps[${index}][description]`" x-model="step.description" readonly />
                          <input class="hidden" :name="`steps[${index}][completed]`" x-model="step.completed ? '1' : '0'" readonly />
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
                      >
                      <button 
                          type="button" 
                          data-test="submit-new-step-button"
                          class="rotate-45 form-muted-icon" 
                          @click="steps.push({ description: newStep.trim(), completed: false }); newStep = ''"
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
              <button type="submit" class="btn">{{ $idea->exists ? 'Update' : 'Create' }}</button>
          </div>
      </div>
  </form>


  @if ($idea->image_path)
    <form method="POST" action="{{ route('ideas.image.destroy', $idea) }}" id="delete-image-form">
      @csrf
      @method("DELETE")
    </form>
  @endif
</x-modal>