@props(['label'])

<div>
    <h3 class="font-semibold text-gray-500 text-sm uppercase tracking-wide">{{ $label }}</h3>
    <div class="text-gray-800 text-lg mt-1">
        {{ $slot }}
    </div>
</div>
