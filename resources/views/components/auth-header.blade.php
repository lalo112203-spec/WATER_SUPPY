@props([
    'title',
    'description',
    'size' => 'lg',
])

<div class="flex w-full flex-col text-center">
    <flux:heading :size="$size">{{ $title }}</flux:heading>
    @if(isset($description) && $description)
        <flux:subheading>{{ $description }}</flux:subheading>
    @endif
</div>
