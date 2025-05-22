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
    <div class="p-5 rounded-xl border shadow-sm transition-all duration-200 
                {{ $n->read_at 
                    ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:shadow-md' 
                    : 'bg-blue-50 dark:bg-blue-900 border-blue-300 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-800' }}">

        <div class="flex items-center gap-3">
            <div class="shrink-0">
                <svg class="w-6 h-6 {{ $n->read_at ? 'text-gray-400 dark:text-gray-500' : 'text-blue-600 dark:text-blue-300' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 
                        .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>

            <div class="flex-grow">
                <p class="text-gray-800 dark:text-gray-100">
                    {{ $n->data['mensaje'] }}
                </p>
                <div class="mt-2 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $n->created_at->diffForHumans() }}</span>
                    </div>

                    @if (is_null($n->read_at))
                        <form action="{{ route('notificaciones.marcar', $n->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 dark:text-blue-300 bg-blue-100 dark:bg-blue-800 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700">
                                Marcar como leída
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-600 dark:text-green-300 bg-green-100 dark:bg-green-800 rounded-full">
                            Leída
                        </span>
                    @endif
                </div>
            </div>
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