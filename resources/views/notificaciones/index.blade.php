<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">🔔 Notificaciones</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10">
        @forelse ($notificaciones as $n)
            <div class="bg-white dark:bg-gray-800 rounded shadow p-4 mb-4">
                <div class="text-gray-800 dark:text-gray-200">
                    {{ $n->data['mensaje'] ?? 'Notificación' }}
                </div>
                <div class="text-sm text-gray-500">{{ $n->created_at->diffForHumans() }}</div>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No hay notificaciones recientes.</p>
        @endforelse

        <div class="mt-6">
            {{ $notificaciones->links() }}
        </div>
    </div>
</x-app-layout>