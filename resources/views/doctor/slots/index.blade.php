<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des disponibilités') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Formulaire d'ajout -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Ajouter des créneaux</h2>
                <form method="POST" action="{{ route('slots.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    
                    <!-- Date -->
                    <div>
                        <x-input-label for="date" value="Date" />
                        <x-text-input id="date" name="date" type="date" class="mt-2 mb-2 block w-full" :value="old('date')" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <!-- Heure début -->
                    <div>
                        <x-input-label for="start_time" value="Heure de début" />
                        <x-text-input id="start_time" name="start_time" type="time" class="mt-2 mb-2 block w-full" :value="old('start_time')" required />
                        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>

                    <!-- Heure fin -->
                    <div>
                        <x-input-label for="end_time" value="Heure de fin" />
                        <x-text-input id="end_time" name="end_time" type="time" class="mt-2 mb-2 block w-full" :value="old('end_time')" required />
                        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                    </div>

                    <!-- Durée créneau (optionnel) -->
                    <div>
                        <x-input-label for="duration" value="Durée (min) - Optionnel" />
                        <select id="duration" name="duration" class="mt-2 mb-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Créneau unique</option>
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                            <option value="45">45 min</option>
                            <option value="60">1h</option>
                        </select>
                        <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                    </div>

                    <div class="md:col-span-4 flex justify-end mt-3">
                        <x-primary-button>
                            {{ __('Générer les créneaux') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Liste des créneaux -->
            <div x-data class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Vos prochains créneaux</h2>
                
                @if($slots->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($slots as $slot)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $slot->start_time->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($slot->is_booked)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Réservé
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Disponible
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($slot->appointment && $slot->appointment->patient)
                                                {{ $slot->appointment->patient->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($slot->appointment && $slot->appointment->reason)
                                                {{ Str::limit($slot->appointment->reason, 30) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(!$slot->is_booked)
                                                <button type="button" 
                                                        class="text-red-600 hover:text-red-900" 
                                                        @click="$dispatch('set-action-delete-slot', '{{ url('/slots') }}/{{ $slot->id }}'); $dispatch('open-modal-delete-slot')">
                                                    Supprimer
                                                </button>
                                            @else
                                                <span class="text-gray-400">Non réservable</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Aucun créneau disponible.</p>
                @endif
                
                <x-modal-confirm 
                    id="delete-slot" 
                    title="Supprimer le créneau" 
                    message="Êtes-vous sûr de vouloir supprimer ce créneau ?" 
                    confirmText="Supprimer"
                    method="DELETE"
                />
            </div>
        </div>
    </div>
</x-app-layout>
