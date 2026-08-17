@props([
    'sidebar' => false,
])

@php
    $ajuste = \App\Models\Ajuste::first();
    $logoUrl = $ajuste && $ajuste->logo ? asset('storage/' . $ajuste->logo) : null;
    $appName = $ajuste ? $ajuste->nombre : config('app.name', 'Laravel');
@endphp

@if($sidebar)
    <flux:sidebar.brand :name="$appName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground overflow-hidden">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="w-full h-full object-cover">
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$appName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground overflow-hidden">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="w-full h-full object-cover">
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:brand>
@endif
