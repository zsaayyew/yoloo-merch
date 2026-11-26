{{--
    FIN - Menampilkan snapshot (options_snapshot / estimate_snapshot) sebagai daftar label: nilai.
    Pemakaian : @include('admin.orders.sections.snapshot-list', ['data' => $item->options_snapshot, 'money' => false])
    Catatan   : format JSON snapshot ditulis ORD. Partial ini membaca bentuk umum
                (key => nilai, atau daftar berisi group/name/label dan value). Sesuaikan bila format ORD berbeda.
--}}
@php
    $rows = [];
    foreach ((array) ($data ?? []) as $key => $val) {
        if (is_array($val)) {
            $label = $val['group'] ?? $val['group_name'] ?? $val['label'] ?? $val['name'] ?? (is_string($key) ? $key : null);
            $value = $val['value'] ?? $val['value_name'] ?? $val['selected'] ?? null;
            if ($value === null) {
                $value = json_encode($val, JSON_UNESCAPED_UNICODE);
            } elseif (is_array($value)) {
                $value = implode(', ', array_map(fn ($v) => is_scalar($v) ? $v : json_encode($v, JSON_UNESCAPED_UNICODE), $value));
            }
        } else {
            $label = is_string($key) ? $key : null;
            $value = $val;
        }

        if (($money ?? false) && is_numeric($value)) {
            $value = \App\Support\Money::format((int) $value);
        }

        $rows[] = [$label ? str_replace('_', ' ', ucfirst((string) $label)) : null, is_bool($value) ? ($value ? 'Ya' : 'Tidak') : $value];
    }
@endphp

@if (count($rows))
    <dl class="grid grid-cols-1 gap-x-4 gap-y-1 text-sm sm:grid-cols-2">
        @foreach ($rows as [$label, $value])
            <div class="flex gap-2">
                @if ($label)
                    <dt class="text-gray-500">{{ $label }}:</dt>
                @endif
                <dd class="font-medium text-gray-900">{{ $value }}</dd>
            </div>
        @endforeach
    </dl>
@else
    <p class="text-sm text-gray-500">-</p>
@endif
