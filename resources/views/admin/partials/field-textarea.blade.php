{{--
    ADM - Textarea dengan label dan pesan galat
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Variabel: $name (notasi titik), $label, $value, $rows, $hint, $required
--}}
@php
    $htmlName = collect(explode('.', $name))
        ->map(fn (string $part, int $index) => $index === 0 ? $part : '['.$part.']')
        ->implode('');
    $inputId = 'field-'.str_replace(['.', '_'], '-', $name);
@endphp

<div>
    <label for="{{ $inputId }}" class="block text-sm font-bold text-[#181818]">
        {{ $label }}@if (! empty($required)) <span class="text-[#b3261e]" aria-hidden="true">*</span>@endif
    </label>

    <textarea id="{{ $inputId }}"
              name="{{ $htmlName }}"
              rows="{{ $rows ?? 4 }}"
              @if (! empty($required)) required @endif
              class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm leading-6 text-[#181818] focus:border-[#14747b] focus:outline-none focus:ring-2 focus:ring-[#58C0C8]/50">{{ old($name, $value ?? '') }}</textarea>

    @isset($hint)
        <p class="mt-1 text-xs text-[#181818]/50">{{ $hint }}</p>
    @endisset

    @error($name)
        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
    @enderror
</div>
