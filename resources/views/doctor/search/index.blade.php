<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trouver un médecin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Search Filter Card -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Rechercher') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Filtrer par spécialité ou par ville.") }}
                        </p>
                    </header>

                    <form method="GET" action="{{ route('doctor.search') }}" class="mt-6 space-y-6 md:space-y-0 md:grid md:grid-cols-3 md:gap-6">
                        <div>
                            <x-input-label for="specialty_id" :value="__('Spécialité')" />
                            <select id="specialty_id" name="specialty_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Toutes les spécialités') }}</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="city" :value="__('Ville')" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="request('city')" placeholder="Ex: Paris" />
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full justify-center h-10">
                                {{ __('Rechercher') }}
                            </x-primary-button>
                        </div>
                    </form>
                </section>
            </div>

            <!-- Results List -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                     <h3 class="text-lg font-medium text-gray-900">
                        {{ $doctors->count() }} {{ Str::plural('résultat', $doctors->count()) }} trouvé(s)
                    </h3>
                </div>

                <ul class="divide-y divide-gray-200">
                    @forelse($doctors as $doctor)
                        <li class="p-6 hover:bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center shrink-0">
                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $doctor->user->name }}</h4>
                                    <p class="text-indigo-600 font-medium">{{ $doctor->specialty->name }}</p>
                                    <div class="text-gray-500 text-sm mt-1">
                                        <p>{{ $doctor->address }}</p>
                                        <p>{{ $doctor->zip_code }} {{ $doctor->city }}</p>
                                    </div>
                                    @if($doctor->bio)
                                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $doctor->bio }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <a href="{{ route('patient.booking.create', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Prendre rendez-vous
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="p-6 text-center text-gray-500">
                            Aucun médecin trouvé pour ces critères.
                        </li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
