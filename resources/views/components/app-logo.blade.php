@props([
    'sidebar' => false,
])

@if($sidebar)
    <a {{ $attributes->merge(['class' => 'flex items-center gap-4 px-2 py-2 dwss-logo-container transition-all duration-300']) }}>
        <div class="flex aspect-square size-12 items-center justify-center rounded-full bg-white/10/10 p-0.5 shadow-[0_0_18px_rgba(255,255,255,0.12)] border border-white/20 shrink-0 dwss-logo transition-all duration-300">
            <x-app-logo-icon class="size-10 dwss-logo-icon transition-all duration-300" />
        </div>
        <div class="flex flex-col leading-none dwss-brand-text transition-all duration-300">
            <span class="text-[32px] font-black tracking-normal whitespace-nowrap drop-shadow-[0_0_12px_rgba(34,211,238,0.7)]" 
                style="color: #22d3ee !important; @if(optional(auth()->user())->font_family) font-family: {{ auth()->user()->font_family }} !important; @endif @if(optional(auth()->user())->text_stroke_width && optional(auth()->user())->text_stroke_color) -webkit-text-stroke: {{ auth()->user()->text_stroke_width }} {{ auth()->user()->text_stroke_color }}; paint-order: stroke fill; @endif">D.W.S.S</span>
        </div>
    </a>
@else
    <style>
        .dwss-brand-desktop > div.truncate {
            white-space: normal !important;
            font-size: 32px !important;
            line-height: 1.2 !important;
            color: #22d3ee !important;
            font-weight: 900 !important;
            letter-spacing: normal !important;
            filter: drop-shadow(0 0 12px rgba(34,211,238,0.7)) !important;
        }
        .dwss-logo-desktop {
            width: 3.5rem !important;
            height: 3.5rem !important;
        }
        .dwss-logo-desktop svg {
            width: 2.75rem !important;
            height: 2.75rem !important;
        }
    </style>
    <flux:brand name="D.W.S.S" class="dwss-brand-desktop" {{ $attributes }}>
        <x-slot name="logo" class="dwss-logo-desktop flex aspect-square items-center justify-center rounded-full bg-white/10/10 p-0.5 shadow-[0_0_15px_rgba(255,255,255,0.1)] border border-white/20">
            <x-app-logo-icon class="dwss-logo-desktop-icon" />
        </x-slot>
    </flux:brand>
@endif
