<x-pulse::card :cols="$cols" :rows="$rows" class="overflow-hidden">
    <x-pulse::card-header
        name="Application Atlas"
        title="Application Surface Area summary."
        details="total unique structure counts"
    >
        <x-slot:icon>
            <x-pulse::icons.rectangle-stack />
        </x-slot:icon>
    </x-pulse::card-header>

    <div class="px-4 py-3 grid grid-cols-2 gap-4">
        <div class="flex flex-col">
            <span class="text-xs text-gray-500 uppercase tracking-widest font-bold">Routes</span>
            <span class="text-2xl font-bold">{{ $routes }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-xs text-gray-500 uppercase tracking-widest font-bold">Jobs</span>
            <span class="text-2xl font-bold">{{ $jobs }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-xs text-gray-500 uppercase tracking-widest font-bold">Commands</span>
            <span class="text-2xl font-bold">{{ $commands }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-xs text-gray-500 uppercase tracking-widest font-bold">Events</span>
            <span class="text-2xl font-bold">{{ $events }}</span>
        </div>
    </div>
</x-pulse::card>
