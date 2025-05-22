<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            ✏️ Editar Patrón de Vulnerabilidad
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
       <form method="POST" action="{{ route('patrones.update', $patron->id) }}" class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-6">
    @csrf
    @method('PUT')

    @include('patrones.form', ['patron' => $patron])

    <div class="flex justify-end">
        <a href="{{ route('patrones.index') }}"
           class="px-4 py-2 text-sm bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</a>
        <button type="submit"
                class="ml-2 px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700">
            Actualizar
        </button>
    </div>
</form>

    </div>
</x-app-layout>
