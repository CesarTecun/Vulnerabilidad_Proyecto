@php
    // Actualizar y cargar notificaciones pendientes de una sola vez
    $pendientes = optional(Auth::user())->unreadNotifications()->take(3)->get();
@endphp

@if($pendientes && $pendientes->isNotEmpty())
<style>
    .notification-container {
        position: fixed;
        top: 75px;
        right: 20px;
        z-index: 1000;
        width: 320px;
        max-width: 90vw;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .notification-item:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    .dark .notification-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
</style>

<div x-data="{ show: true }" 
     x-init="setTimeout(() => show = false, 6000)" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="notification-container bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
     
    <div class="flex justify-between items-center px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
        <div class="flex items-center space-x-2">
            <span class="text-yellow-500">🔔</span>
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Tienes notificaciones</h3>
        </div>
        <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach($pendientes as $n)
            <li class="notification-item px-4 py-3 transition-colors duration-150">
                 <a href="{{ route('notificaciones.marcarYRedirigir', $n->id) }}" class="block">
                    <p class="text-sm text-gray-700 dark:text-gray-300 truncate">
                        {{ $n->data['mensaje'] ?? 'Nueva notificación' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $n->created_at->diffForHumans() }}
                    </p>
                </a>
            </li>
        @endforeach
    </ul>
    
    @if($pendientes->count() > 3)
    <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700 text-center border-t border-gray-200 dark:border-gray-600">
        <a href="{{ route('notifications') }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
            Ver todas ({{ Auth::user()->unreadNotifications()->count() }})
        </a>
    </div>
    @endif
</div>
@endif