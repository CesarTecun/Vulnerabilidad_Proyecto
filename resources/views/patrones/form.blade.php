<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $patron->nombre ?? '') }}"
           class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
    @error('nombre')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Regex</label>
    <input type="text" name="regex" value="{{ old('regex', $patron->regex ?? '') }}"
           class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
    @error('regex')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Criticidad</label>
    <select name="criticidad"
            class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @foreach(['Alta', 'Media', 'Baja'] as $nivel)
            <option value="{{ $nivel }}" @selected(old('criticidad', $patron->criticidad ?? '') == $nivel)>
                {{ $nivel }}
            </option>
        @endforeach
    </select>
    @error('criticidad')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>