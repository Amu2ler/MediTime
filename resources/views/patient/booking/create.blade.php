<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Finaliser mon rendez-vous') }}
            </h2>
            
            <a href="{{ isset($rescheduleAppointment) ? route('dashboard') : route('doctor.search') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Étape précédente
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col lg:flex-row items-start justify-center gap-32">
                
                <!-- Left Column: Reason Selection (Main) -->
                <div class="w-full flex-1">


                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Choisissez votre motif de consultation</h3>
                        
                        <form id="booking-form" method="POST" action="{{ route('patient.booking.store') }}" x-data="{ selectedReason: null }">
                            @csrf
                            @if($selectedSlot)
                                <input type="hidden" name="slot_id" value="{{ $selectedSlot->id }}">
                            @endif
                            @if($rescheduleAppointment)
                                <input type="hidden" name="reschedule_id" value="{{ $rescheduleAppointment->id }}">
                                
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
                                    <div class="mb-4 flex items-center gap-2 text-blue-800">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                        </svg>
                                        <h4 class="font-bold text-lg">Modification de rendez-vous</h4>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Old Appointment -->
                                        <div>
                                            <p class="font-bold text-blue-800 text-xs uppercase tracking-wider mb-2">Ancien Rendez-vous</p>
                                            <div class="bg-blue-100/50 p-3 rounded-md border border-blue-100">
                                                <p class="text-sm text-blue-900 font-bold mb-1">
                                                    {{ $rescheduleAppointment->slot->start_time->format('d/m/Y à H:i') }}
                                                </p>
                                                <p class="text-xs text-blue-700 leading-snug">
                                                    {{ $rescheduleAppointment->reason }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- New Appointment -->
                                        <div>
                                            <p class="font-bold text-blue-800 text-xs uppercase tracking-wider mb-2">Nouveau Rendez-vous</p>
                                            <div class="bg-white p-3 rounded-md border border-blue-200 shadow-sm">
                                                <p class="text-sm text-blue-900 font-bold mb-1">
                                                    @if($selectedSlot)
                                                        {{ $selectedSlot->start_time->format('d/m/Y à H:i') }}
                                                    @else
                                                        <span class="text-orange-600">⚠️ Aucun créneau sélectionné</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-blue-500 italic">
                                                    Sélectionnez le nouveau motif ci-dessous
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 gap-5">
                                @forelse($consultationReasons as $reason)
                                    <label class="group relative flex items-center p-6 border-2 rounded-2xl cursor-pointer hover:bg-blue-50 hover:border-blue-400 transition-all duration-200 border-gray-100 shadow-sm gap-4"
                                        :class="{ 'border-blue-600 bg-blue-50 ring-1 ring-blue-600': selectedReason == {{ $reason->id }} }">

                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="reason_id" value="{{ $reason->id }}" 
                                                   x-model="selectedReason" required class="h-6 w-6 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        </div>

                                        <div class="flex-1">
                                            <span class="block text-lg font-semibold text-gray-900 group-hover:text-blue-700">{{ $reason->name }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <p>Aucun motif spécifique trouvé pour cette spécialité.</p>
                                    </div>
                                @endforelse
                            </div>
                            @error('reason_id')
                                <p class="text-red-600 font-medium mt-4 text-center bg-red-50 p-3 rounded-lg border border-red-200">
                                    Veuillez sélectionner un motif de consultation.
                                </p>
                            @enderror

                            <!-- Optional Message -->
                            <div class="mt-8 transition" x-show="selectedReason" x-transition>
                                <label for="note" class="block text-sm font-medium text-gray-700 mb-2">Message pour le praticien (Mettre N.A si aucun)</label>
                                <textarea id="note" name="reason" rows="3" class="shadow-sm block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Ex: Première consultation, douleur dentaire..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Summary Card (Sidebar) -->
                <div class="mt-8 md:mt-0 md:w-80 lg:w-96 shrink-0 md:sticky md:top-8">
                    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0 font-bold text-lg uppercase">
                                    {{ substr($doctor->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Dr. {{ $doctor->user->name }}</h4>
                                    <p class="text-sm text-blue-600 font-medium">{{ $doctor->specialty->name }}</p>
                                    <div class="flex items-center gap-1 mt-1 text-xs text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span>Cabinet médical</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h5 class="font-bold text-gray-900 mb-4 text-xs uppercase tracking-wider text-gray-500">Mon Rendez-vous</h5>
                            <ul class="space-y-4 text-sm text-gray-600">
                                <li class="flex items-start gap-3">
                                    <div class="mt-0.5 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $doctor->address }}</div>
                                        <div>{{ $doctor->zip_code }} {{ $doctor->city }}</div>
                                    </div>
                                </li>
                                <li class="pt-4 border-t border-gray-100 flex items-start gap-3">
                                    <div class="mt-0.5 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                    <div class="flex-1">
                                        @if($selectedSlot)
                                            <div class="font-bold text-gray-900 capitalize">{{ $selectedSlot->start_time->translatedFormat('l j F Y') }}</div>
                                            <div class="text-lg text-blue-600 font-bold mt-1">{{ $selectedSlot->start_time->format('H:i') }}</div>
                                        @else
                                            <span class="text-orange-500 font-medium italic">Aucun créneau choisi</span>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                            
                            <!-- Action Button Moved Here -->
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <button type="submit" 
                                        form="booking-form"
                                        style="background-color: {{ isset($rescheduleAppointment) ? '#2563eb' : '#eab308' }}; color: white;"
                                        class="w-full flex justify-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-sm font-black text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all uppercase tracking-widest">
                                    {{ isset($rescheduleAppointment) ? 'MODIFIER LE RENDEZ-VOUS' : 'CONFIRMER LE RDV' }}
                                </button>
                                <p class="text-xs text-center text-gray-400 mt-2">En cliquant sur "Confirmer", vous acceptez les CGU.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
