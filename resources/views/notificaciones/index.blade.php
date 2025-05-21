<x-app-layout>
    <x-slot name="header">
        <h2 class="flex items-center text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Notificaciones
        </h2>
    </x-slot>
    
    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if($notificaciones->count() > 0)
                <form method="POST" action="{{ route('notificaciones.marcarTodas') }}" class="mb-6">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Marcar todas como leídas
                    </button>
                </form>
            @endif

            <div class="space-y-4">
                @forelse ($notificaciones as $n)
                    <div class="p-4 rounded-lg shadow transition-all duration-200 border border-gray-200 dark:border-gray-600
                              {{ $n->read_at ? 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700' : 'bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800' }}">
                        <p class="text-gray-800 dark:text-gray-100">
                            {{ $n->data['mensaje'] }}
                        </p>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400">
                                {{ $n->created_at->diffForHumans() }}
                            </span>
                            
                            @if (is_null($n->read_at))
                                <form action="{{ route('notificaciones.marcar', $n->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-200 hover:underline text-xs font-medium focus:outline-none">
                                        Marcar como leída
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium">
                                    Leída
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay notificaciones</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No tienes notificaciones pendientes.</p>
                    </div>
                @endforelse

                @if($notificaciones->hasPages())
                    <div class="mt-6 bg-white dark:bg-gray-800 px-4 py-3 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                        {{ $notificaciones->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>