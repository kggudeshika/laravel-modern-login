{{--
    Shared by create.blade.php and edit.blade.php.
    Shows every common form control: text, select, radio, date, checkbox, textarea.
--}}

<x-field name="title" label="Title" :value="$post->title"
         placeholder="A short, catchy title" required autofocus />

<div class="form-grid">
    <x-field name="category" label="Category" type="select"
             :options="\App\Models\Post::CATEGORIES" :value="$post->category" required />

    <x-field name="published_on" label="Published date" type="date"
             :value="$post->published_on?->format('Y-m-d')"
             hint="Required when the status is Published." />
</div>

<fieldset class="field">
    <legend>Status</legend>
    <div class="radio-group">
        @foreach (\App\Models\Post::STATUSES as $value => $label)
            <label class="radio-card">
                <input type="radio" name="status" value="{{ $value }}" @checked(old('status', $post->status) === $value)>
                <span>{{ $label }}</span>
            </label>
        @endforeach
    </div>
    @error('status')
        <p class="field-error">{{ $message }}</p>
    @enderror
</fieldset>

<x-field name="body" label="Content" type="textarea" rows="8" :value="$post->body"
         placeholder="Write your post…" required />

<label class="checkbox">
    {{-- Hidden input sends "0" when the box is unticked. --}}
    <input type="hidden" name="is_featured" value="0">
    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>
    <span>Feature this post on the dashboard</span>
</label>
