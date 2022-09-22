@props([
    'class' => 'w-24',
    'alt' => 'Bolt'
])
<img src="{{ asset('favicon.svg') }}" alt="{{ $alt }}" class="text-center mx-auto {{ $class }}" />
