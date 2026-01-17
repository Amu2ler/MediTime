<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Gestion des Spécialités') }}
            </h2>
            <div class="flex items-center gap-3">
                <!-- Sort Button -->
                <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'desc' ? 'asc' : 'desc']) }}" 
                   class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full text-sm font-medium shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                    </svg>
                    {{ request('sort') === 'desc' ? 'Nom (Z-A)' : 'Nom (A-Z)' }}
                </a>

                <!-- Add Button (Fixed Visibility) -->
                <a href="{{ route('specialties.create') }}" 
                   class="px-4 py-2 rounded-full text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2"
                   style="background-color: #0596de; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter une spécialité
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search Form -->
            <form method="GET" action="{{ route('specialties.index') }}" class="mb-6 flex items-center gap-4">
                <div class="flex-1 max-w-md relative">
                    <x-text-input id="search" name="search" type="text" 
                                  class="block w-full h-10 pl-10 pr-4 rounded-full" 
                                  placeholder="Rechercher une spécialité..." 
                                  :value="request('search')" />
                </div>

                @if(request('search'))
                    <a href="{{ route('specialties.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-colors">
                        Effacer
                    </a>
                @endif
                
                <x-primary-button class="h-10 rounded-full">
                    Rechercher
                </x-primary-button>
            </form>
            
            <!-- Flash Messages -->
            <div class="text-center">
                @if (session('success'))
                    <div class="mb-6 px-6 py-2 rounded-full inline-block font-medium shadow-sm transition-all"
                         style="background-color: #d1fae5; color: #065f46; border: 1px solid #34d399;">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 px-6 py-2 rounded-full inline-block font-medium shadow-sm transition-all"
                         style="background-color: #ffedd5; color: #9a3412; border: 1px solid #fb923c;">
                        {{ session('warning') }}
                    </div>
                @endif

                @if (session('danger') || session('error'))
                    <div class="mb-6 px-6 py-2 rounded-full inline-block font-medium shadow-sm transition-all"
                         style="background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171;">
                        {{ session('danger') ?? session('error') }}
                    </div>
                @endif
            </div>

            <!-- Specialties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($specialties as $specialty)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow duration-200 group">
                        <div class="flex items-center gap-4">

                            <span class="text-lg font-bold text-gray-900">{{ $specialty->name }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('specialties.edit', $specialty) }}" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>

                            <form action="{{ route('specialties.destroy', $specialty) }}" method="POST" onsubmit="return confirm('Supprimer cette spécialité ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <p class="text-gray-500 font-medium">Aucune spécialité enregistrée.</p>
                        <a href="{{ route('specialties.create') }}" class="mt-4 inline-block text-blue-600 hover:underline">Commencer par en ajouter une</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
