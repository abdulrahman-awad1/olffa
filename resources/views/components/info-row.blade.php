@props(['label', 'value' => null])

<div class="grid grid-cols-[1fr_2fr] gap-4 border-b border-emerald-900/5 py-2.5 text-sm last:border-b-0">
    <span class="text-ink/50">{{ $label }}</span>
    <span>{{ filled($value) ? $value : '—' }}</span>
</div>
