{{--
    ADM - Checkbox dengan label
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Variabel: $name (notasi titik), $label, $checked, $hint
--}}
@php
    $htmlName = collect(explode('.', $name))
        ->map(fn (string $part, int $index) => $index === 0 ? $part : '['.$part.']')
        ->implode('');
    $inputId = 'field-'.str_replace(['.', '_'], '-', $name);
@endphp

<div>
    <label for="{{ $inputId }}" class="inline-flex items-start gap-2 text-sm text-[#181818]">
        <input type="hidden" name="{{ $htmlName }}" value="0">
        <input id="{{ $inputId }}" type="checkbox" name="{{ $htmlName }}" value="1"
               @checked((bool) old($name, $checked ?? false))
               class="mt-0.5 h-4 w-4 rounded border-[#181818]/30 text-[#14747b] focus:ring-[#58C0C8]">
        <span class="font-semibold">{{ $label }}</span>
    </label>

    @isset($hint)
        <p class="mt-1 pl-6 text-xs text-[#181818]/50">{{ $hint }}</p>
    @endisset

    @error($name)
        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
    @enderror
</div>
