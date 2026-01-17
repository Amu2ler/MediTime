<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Conditional Content based on Role -->
            @if(Auth::user()->role === 'doctor')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Votre agenda (Prochains rendez-vous)</h3>
                    
                    @if($appointments->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
                            @foreach($appointments as $appointment)
                                <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col h-full relative group">
                                    
                                    <!-- Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="font-bold text-2xl text-indigo-600">
                                                {{ $appointment->slot->start_time->format('H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-500 font-medium capitalize">
                                                {{ $appointment->slot->start_time->isoFormat('dddd D MMMM YYYY') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="inline-block px-2 py-1 text-xs font-bold uppercase tracking-wider rounded text-green-700 bg-green-50">
                                                Confirmé
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Body -->
                                    <div class="border-t border-gray-100 pt-4 mb-6 flex-grow">
                                        <p class="font-bold text-gray-900 truncate text-lg">{{ $appointment->patient->name }}</p>
                                        <p class="text-sm text-gray-500 mb-3">{{ $appointment->patient->email }}</p>

                                        <!-- Reason (Styled like Specialty/Address) -->
                                        <p class="text-sm text-blue-600 font-medium mb-1">Motif de consultation</p>
                                        <p class="text-sm text-gray-500 leading-relaxed">
                                            {{ $appointment->reason ?? 'Non renseigné' }}
                                        </p>
                                    </div>

                                    <!-- Footer (Action) -->
                                    <div class="flex items-center gap-3 mt-auto pt-4 border-t border-gray-100">
                                        <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-center px-4 py-2 rounded-lg text-sm font-bold transition-colors"
                                                    style="background-color: #fee2e2; color: #b91c1c; width: 100%; text-align: center;" 
                                                    onclick="return confirm('Annuler ce rendez-vous ?')">
                                                Annuler
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Aucun rendez-vous à venir.</p>
                    @endif
                </div>

            @else
                <!-- Patient View -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Mes rendez-vous</h3>

                    @if($appointments->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
                            @foreach($appointments as $appointment)
                                <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col h-full relative group">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="font-bold text-2xl text-indigo-600">
                                                {{ $appointment->slot->start_time->format('H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-500 font-medium capitalize">
                                                {{ $appointment->slot->start_time->isoFormat('dddd D MMMM YYYY') }}
                                            </p>
                                        </div>
                                        <div>
                                             <span class="inline-block px-2 py-1 text-xs font-bold uppercase tracking-wider rounded text-green-700 bg-green-50">
                                                Confirmé
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t border-gray-100 pt-4 mb-6 flex-grow">
                                        <p class="font-bold text-gray-900 truncate text-lg">Dr. {{ $appointment->slot->user->name }}</p>
                                        @if($appointment->slot->user->doctorProfile)
                                            <p class="text-sm text-blue-600 font-medium mb-1">
                                                {{ $appointment->slot->user->doctorProfile->specialty->name ?? 'Médecin' }}
                                            </p>
                                            <p class="text-sm text-gray-500 leading-relaxed">
                                                {{ $appointment->slot->user->doctorProfile->address }}<br>
                                                {{ $appointment->slot->user->doctorProfile->zip_code }} {{ $appointment->slot->user->doctorProfile->city }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3 mt-auto pt-4 border-t border-gray-100">
                                        <a href="{{ route('doctor.search', ['doctor_id' => $appointment->slot->user->doctorProfile->id, 'reschedule_id' => $appointment->id]) }}" 
                                           class="flex-1 text-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold hover:bg-indigo-100 transition-colors"
                                           style="text-align: center;">
                                            Modifier
                                        </a>

                                        <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-center px-4 py-2 rounded-lg text-sm font-bold transition-colors"
                                                    style="background-color: #fee2e2; color: #b91c1c; width: 100%; text-align: center;"
                                                    onclick="return confirm('Annuler votre rendez-vous ?')">
                                                Annuler
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">Vous n'avez aucun rendez-vous prévu.</p>
                            <a href="{{ route('doctor.search') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Trouver un médecin
                            </a>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
