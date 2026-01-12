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
                    <!-- Availability Filter (New) -->
                    <!-- Availability Filter (New) -->
                        <div class="md:col-span-3 flex flex-wrap gap-4 mt-4 justify-center">
                            <button name="availability" value="today" type="submit" 
                                class="px-6 py-2 rounded-full font-bold transition-all duration-200 border shadow-sm"
                                style="{{ request('availability') == 'today' ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                Disponible aujourd'hui
                            </button>
                            
                            <button name="availability" value="week" type="submit" 
                                class="px-6 py-2 rounded-full font-bold transition-all duration-200 border shadow-sm"
                                style="{{ request('availability') == 'week' ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                Cette semaine
                            </button>
                            
                            <button name="availability" value="" type="submit" 
                                class="px-6 py-2 rounded-full font-bold transition-all duration-200 border shadow-sm"
                                style="{{ !request('availability') ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                Tous
                            </button>
                        </div>
                    </form>
                </section>
            </div>

            <!-- Results List (Wide Cards) -->
            <style>
                .doctor-card {
                    display: flex;
                    flex-direction: column;
                    background: white;
                    border-radius: 0.5rem;
                    overflow: hidden;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                    margin-bottom: 1.5rem;
                    transition: box-shadow 0.2s;
                }
                .doctor-card:hover {
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                @media (min-width: 768px) {
                    .doctor-card {
                        flex-direction: row;
                    }
                }
                
                .doctor-info {
                    padding: 1.5rem;
                    flex: 1; /* Takes available space */
                    border-bottom: 1px solid #f3f4f6;
                }
                @media (min-width: 768px) {
                    .doctor-info {
                        width: 45%;
                        border-bottom: none;
                        border-right: 1px solid #f3f4f6;
                    }
                }

                .doctor-calendar {
                    background-color: #f8fafc; /* Blueish gray background like design */
                    display: flex;
                    flex-direction: column;
                }
                @media (min-width: 768px) {
                    .doctor-calendar {
                        width: 55%;
                    }
                }

                .calendar-grid {
                    display: grid;
                    grid-template-columns: repeat(5, 1fr);
                    text-align: center;
                }
                .calendar-header {
                    padding: 0.75rem 0;
                    border-bottom: 1px solid #e2e8f0;
                    font-size: 0.75rem;
                    line-height: 1.25;
                    font-weight: 600;
                    color: #1e293b;
                }
                .calendar-col {
                    border-right: 1px solid #e2e8f0;
                    padding: 0.5rem 0.25rem;
                    min-height: 200px;
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                    align-items: center;
                }
                .calendar-col:last-child {
                    border-right: none;
                }
                
                .slot-pill {
                    display: block;
                    width: 100%;
                    max-width: 60px;
                    background-color: #c7d2fe; /* Indigo 200 equivalent */
                    color: #1e1b4b; /* Indigo 950 */
                    font-weight: 700;
                    font-size: 0.75rem;
                    padding: 0.25rem 0;
                    border-radius: 4px;
                    text-decoration: none;
                    transition: background-color 0.2s;
                }
                .slot-pill:hover {
                    background-color: #a5b4fc; /* Indigo 300 */
                }
                .no-slot {
                    color: #94a3b8;
                    font-size: 1.25rem;
                    line-height: 1;
                }
            </style>

            <div class="space-y-6">
                @forelse($doctors as $doctor)
                    <div class="doctor-card">
                        <!-- Left: Doctor Info -->
                        <div class="doctor-info">
                            <div class="flex items-start gap-4">
                                <div class="h-16 w-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0 font-bold text-xl uppercase">
                                    {{ substr($doctor->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-2xl font-bold text-gray-900 leading-tight">
                                        <a href="#" class="hover:underline hover:text-blue-600">Dr {{ $doctor->user->name }}</a>
                                    </h4>
                                    <p class="text-gray-600 font-medium text-base mt-1 mb-3">{{ $doctor->specialty->name }}</p>
                                    
                                    <div class="space-y-2 text-gray-600">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 mt-0.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span class="text-base font-medium">{{ $doctor->address }}<br>{{ $doctor->zip_code }} {{ $doctor->city }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                Conventionné secteur 1
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex gap-3">
                                        <form action="{{ route('patient.booking.create', $doctor) }}" method="GET">
                                            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-md text-sm px-4 py-2 text-center transition">
                                                Prendre rendez-vous
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Mini Calendar -->
                        <div class="doctor-calendar">
                             <!-- Header -->
                             <div class="calendar-grid bg-white">
                                @for($i = 0; $i < 5; $i++)
                                    @php $date = now()->addDays($i); @endphp
                                    <div class="calendar-header">
                                        <div class="text-gray-900 capitalize">{{ $date->translatedFormat('D') }}</div>
                                        <div class="text-gray-500 font-normal">{{ $date->format('d M.') }}</div>
                                    </div>
                                @endfor
                             </div>

                             <!-- Slots Grid -->
                             <div class="calendar-grid flex-1">
                                @for($i = 0; $i < 5; $i++)
                                    @php 
                                        $currentDate = now()->addDays($i);
                                        // Use a fresh query or careful filtering to avoid emptying the collection
                                        $daySlots = $doctor->user->slots->filter(function($slot) use ($currentDate) {
                                            return $slot->start_time->isSameDay($currentDate) 
                                                && $slot->start_time > now()
                                                && !$slot->is_booked;
                                        })->sortBy('start_time')->take(4); // Limit visual clutter
                                    @endphp
                                    
                                    <div class="calendar-col">
                                        @forelse($daySlots as $slot)
                                            <a href="{{ route('patient.booking.create', ['doctor' => $doctor->id, 'slot_id' => $slot->id, 'date' => $currentDate->toDateString()]) }}" 
                                               class="slot-pill bg-[#dcfce7] text-[#166534] hover:bg-[#bbf7d0]">
                                                {{ $slot->start_time->format('H:i') }}
                                            </a>
                                        @empty
                                            <div class="no-slot">-</div>
                                        @endforelse
                                    </div>
                                @endfor
                             </div>


                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500 text-lg">Aucun médecin trouvé pour ces critères.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
