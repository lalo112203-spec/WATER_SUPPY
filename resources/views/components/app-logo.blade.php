@props([
    'sidebar' => false,
])

@if($sidebar)
    <a {{ $attributes->merge(['class' => 'flex items-center gap-3 px-2 py-2']) }}>
        <div class="flex aspect-square size-12 items-center justify-center rounded-full bg-white/10 p-0.5 shadow-[0_0_18px_rgba(255,255,255,0.12)] border border-white/20">
            <x-app-logo-icon class="size-10" />
        </div>
        <div class="flex flex-col leading-none tracking-tighter">
            <span class="text-2xl font-black text-cyan-400 drop-shadow-[0_0_12px_rgba(34,211,238,0.5)]" style="@if(auth()->user()->font_family) font-family: {{ auth()->user()->font_family }} !important; @endif @if(auth()->user()->text_stroke_width && auth()->user()->text_stroke_color) -webkit-text-stroke: {{ auth()->user()->text_stroke_width }} {{ auth()->user()->text_stroke_color }}; paint-order: stroke fill; @endif">D.W.S.S</span>
        </div>
    </a>
@else
    <flux:brand name="D.W.S.S" class="[&>div.truncate]:!whitespace-normal [&>div.truncate]:!text-xs [&>div.truncate]:!leading-tight [&>div.truncate]:!text-white [&>div.truncate]:!font-bold [&>div.truncate]:!tracking-tighter [&>div.truncate]:!drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-10 items-center justify-center rounded-full bg-white/10 p-0.5 shadow-[0_0_15px_rgba(255,255,255,0.1)]">
            <x-app-logo-icon class="size-9" />
        </x-slot>
    </flux:brand>
@endif
