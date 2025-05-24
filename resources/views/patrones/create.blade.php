<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            ➕ Nuevo Patrón de Vulnerabilidad
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('patrones.store') }}"
              class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-6">
            @csrf
            @include('patrones.form', ['patron' => null])
            <div class="flex justify-end">
                <a href="{{ route('patrones.index') }}"
                   class="px-4 py-2 text-sm bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</a>
                <button type="submit"
                        class="ml-2 px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
@auth
    {{-- Notificación flotante --}}
    @php
        Auth::user()->refresh();
        $pendientes = Auth::user()->unreadNotifications()->take(3);
    @endphp

    @if ($pendientes->count())
        {{-- Aquí va el bloque corregido que te mostré arriba --}}
    @endif
@endauth