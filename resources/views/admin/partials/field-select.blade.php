{{--
    ADM - Select dengan label dan pesan galat
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Variabel: $name (notasi titik), $label, $value, $options (array value => label), $placeholder, $required
--}}
@php
    $htmlName = collect(explode('.', $name))
        ->map(fn (string $part, int $index) => $index === 0 ? $part : '['.$part.']')
        ->implode('');
    $inputId = 'field-'.str_replace(['.', '_'], '-', $name);
    $selected = old($name, $value ?? '');
@endphp

<div>
    <label for="{{ $inputId }}" class="block text-sm font-bold text-[#181818]">
        {{ $label }}@if (! empty($required)) <span class="text-[#b3261e]" aria-hidden="true">*</span>@endif
    </label>

    <select id="{{ $inputId }}" name="{{ $htmlName }}"
            @if (! empty($required)) required @endif
            class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm text-[#181818] focus:border-[#14747b] focus:outline-none focus:ring-2 focus:ring-[#58C0C8]/50">
        @isset($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endisset

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $selected === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
    @enderror
</div>
