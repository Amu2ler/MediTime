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
                <h2 class="text-lg font-semibold mb-8">Ajouter des créneaux</h2>

                <form method="POST" action="{{ route('slots.store') }}">
                    @csrf

                    <!-- Inputs Container -->
                    <div class="space-y-8">
                        <!-- Row 1: Date & Duration -->
                        <div class="flex space-x-10 mt-6">
                            <div class="flex-1">
                                <x-input-label for="date" value="Date" />
                                <x-text-input id="date" name="date" type="date" class="mt-2 block w-full" :value="old('date')" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div class="flex-1">
                                <x-input-label for="duration" value="Durée (min) – Optionnel" />
                                <select id="duration" name="duration" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Créneau unique</option>
                                    <option value="15">15 min</option>
                                    <option value="30">30 min</option>
                                    <option value="45">45 min</option>
                                    <option value="60">1h</option>
                                </select>
                                <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Row 2: Start & End Time -->
                        <div class="flex space-x-10">
                            <div class="flex-1">
                                <x-input-label for="start_time" value="Heure de début" />
                                <x-text-input id="start_time" name="start_time" type="time" class="mt-2 block w-full" :value="old('start_time')" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>
                            <div class="flex-1">
                                <x-input-label for="end_time" value="Heure de fin" />
                                <x-text-input id="end_time" name="end_time" type="time" class="mt-2 block w-full" :value="old('end_time')" required />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Bouton -->
                    <div class="mt-6">
                        <x-primary-button class="w-full justify-center">
                            Générer les créneaux
                        </x-primary-button>
                    </div>
                </form>
            </div>


            <!-- Calendrier Hebdomadaire -->
            <div x-data class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Navigation -->
                <div class="flex justify-between items-center mb-6 gap-4">
                    <a
                        href="{{ route('slots.index', ['start_date' => $previousWeek]) }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2 font-medium text-gray-700 shadow-sm whitespace-nowrap"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Semaine précédente
                    </a>

                    <h2 class="text-base md:text-xl font-bold text-gray-800 capitalize bg-gray-50 px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap">
                        {{ $weekLabel }}
                    </h2>

                    <a
                        href="{{ route('slots.index', ['start_date' => $nextWeek]) }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2 font-medium text-gray-700 shadow-sm whitespace-nowrap"
                    >
                        Semaine suivante
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>


            <!-- Styles Adaptation "Trouver un médecin" -->
            <style>
                .calendar-container {
                    background-color: #fff;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    overflow: hidden;
                }
                .calendar-grid-header {
                    display: grid;
                    grid-template-columns: repeat(7, 1fr);
                    background: #f8fafc;
                    border-bottom: 1px solid #e2e8f0;
                }
                .calendar-header-item {
                    padding: 12px 0;
                    text-align: center;
                    border-right: 1px solid #e2e8f0;
                }
                .calendar-header-item:last-child { border-right: none; }
                .calendar-day-name { font-weight: 600; color: #1e293b; font-size: 14px; text-transform: capitalize; }
                .calendar-day-date { font-weight: 400; color: #64748b; font-size: 13px; margin-top: 2px; }
                
                .calendar-grid-body {
                    display: grid;
                    grid-template-columns: repeat(7, 1fr);
                }
                .calendar-col {
                    border-right: 1px solid #e2e8f0;
                    min-height: 300px;
                    padding: 12px 8px;
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                    align-items: center;
                    background: white;
                }
                .calendar-col:last-child { border-right: none; }
                
                .slot-pill {
                    width: 100%;
                    padding: 6px 0;
                    border-radius: 4px;
                    font-size: 13px;
                    font-weight: 700;
                    text-align: center;
                    cursor: pointer;
                    position: relative;
                    transition: all 0.2s;
                }
                /* Free Slots (Green like Search) */
                .slot-pill.free {
                    background-color: #dcfce7;
                    color: #166534;
                }
                .slot-pill.free:hover {
                    background-color: #bbf7d0;
                    transform: translateY(-1px);
                }
                
                /* Booked Slots (Red/Different) */
                .slot-pill.booked {
                    background-color: #fee2e2;
                    color: #991b1b;
                    cursor: default;
                }
                
                .no-slot { color: #cbd5e1; font-size: 24px; margin-top: 40px; }

                /* Hover Delete Icon */
                .delete-overlay {
                    position: absolute; inset: 0;
                    background: rgba(220, 38, 38, 0.9);
                    color: white;
                    display: flex; align-items: center; justify-content: center;
                    border-radius: 4px;
                    opacity: 0; transition: opacity 0.1s;
                }
                .slot-pill.free:hover .delete-overlay { opacity: 1; }
            </style>

            <!-- Calendrier Horizontal (Style Patient) -->
            <div class="calendar-container">
                <!-- Headers -->
                <div class="calendar-grid-header">
                    @for ($i = 0; $i < 7; $i++)
                        @php 
                            $date = $startOfWeek->copy()->addDays($i); 
                            $isToday = $date->isToday();
                        @endphp
                        <div class="calendar-header-item {{ $isToday ? 'bg-blue-50' : '' }}">
                            <div class="calendar-day-name {{ $isToday ? 'text-blue-700' : '' }}">{{ $date->translatedFormat('D') }}</div>
                            <div class="calendar-day-date {{ $isToday ? 'text-blue-600 font-bold' : '' }}">{{ $date->format('d M.') }}</div>
                        </div>
                    @endfor
                </div>

                <!-- Columns -->
                <div class="calendar-grid-body">
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $dayDate = $startOfWeek->copy()->addDays($i);
                            $dayString = $dayDate->format('Y-m-d');
                            $daySlots = $slots[$dayString] ?? collect();
                            $isToday = $dayDate->isToday();
                        @endphp
                        
                        <div class="calendar-col {{ $isToday ? 'bg-blue-50/30' : '' }}">
                            @if($daySlots->isEmpty())
                                <div class="no-slot">-</div>
                            @else
                                @foreach($daySlots as $slot)
                                    @if($slot->is_booked)
                                        <div class="slot-pill booked group" title="{{ $slot->appointment->patient->name ?? 'Patient' }}">
                                            {{ $slot->start_time->format('H:i') }}
                                            <!-- Tooltip -->
                                            <div class="hidden group-hover:block absolute z-50 bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[150px] p-2 bg-gray-900 text-white text-xs rounded shadow-lg">
                                                <p class="font-bold">{{ $slot->appointment->patient->name ?? 'Inconnu' }}</p>
                                                <p class="opacity-75">{{ Str::limit($slot->appointment->reason, 20) }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Delete Action on Click -->
                                        <button type="button" 
                                                class="slot-pill free"
                                                @click="$dispatch('set-action-delete-slot', '{{ route('slots.destroy', $slot) }}'); $dispatch('open-modal-delete-slot')">
                                            <span>{{ $slot->start_time->format('H:i') }}</span>
                                            <span class="delete-overlay">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </span>
                                        </button>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
                
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
