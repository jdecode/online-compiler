@props([
    'class' => 'w-24',
    'alt' => 'Bolt'
])
<span
    title=" {{ config('app.name', 'Laravel') }}"
    class="text-dev-500 text-7xl">
    <x-bolt class="{{ $class }}" alt="{{ $alt }}"></x-bolt>
</span>
