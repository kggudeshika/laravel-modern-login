@props([
    'name',
    'label',
    'type' => 'text',      // any <input> type, or "textarea" / "select"
    'icon' => null,        // optional icon name from <x-icon>
    'value' => null,       // default value (old() input wins after a failed submit)
    'options' => [],       // for type="select": ['value' => 'Label']
    'hint' => null,
    'bag' => 'default',   // error bag name (see ProfileController)
])

{{--
    Reusable form field: label + input + validation error.

    <x-field name="email" label="Email" type="email" icon="mail" />
    <x-field name="body" label="Body" type="textarea" rows="6" />
    <x-field name="category" label="Category" type="select" :options="$categories" />
--}}

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->getBag($bag)->has($name);
    $current = $type === 'password' ? null : old($name, $value);
@endphp

<div class="field">
    <div class="label-row">
        <label for="{{ $id }}">{{ $label }}</label>
        {{ $labelAction ?? '' }}
    </div>

    <div @class(['input-wrap', 'has-error' => $hasError, 'no-icon' => ! $icon])>
        @if ($icon)
            <x-icon :name="$icon" class="input-icon" />
        @endif

        @if ($type === 'textarea')
            <textarea id="{{ $id }}" name="{{ $name }}"
                @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                {{ $attributes->except('id')->merge(['rows' => 5]) }}>{{ $current }}</textarea>
        @elseif ($type === 'select')
            <select id="{{ $id }}" name="{{ $name }}"
                @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                {{ $attributes->except('id') }}>
                <option value="">Choose…</option>
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
                @endforeach
            </select>
        @else
            <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $current }}"
                @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                {{ $attributes->except('id') }}>
            @if ($type === 'password')
                <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false">
                    <x-icon name="eye" class="eye-open" />
                    <x-icon name="eye-off" class="eye-closed" />
                </button>
            @endif
        @endif
    </div>

    @if ($hint && ! $hasError)
        <p class="field-hint">{{ $hint }}</p>
    @endif

    @error($name, $bag)
        <p class="field-error" id="{{ $id }}-error">{{ $message }}</p>
    @enderror
</div>
