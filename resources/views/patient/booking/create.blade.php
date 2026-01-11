<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Doctor Info -->
                <div class="mb-6 border-b pb-4">
                    <h1 class="text-2xl font-bold text-gray-900">Prendre rendez-vous</h1>
                    <div class="mt-2 text-gray-600">
                        <p class="font-semibold text-lg">Dr. {{ $doctor->user->name }}</p>
                        <p class="text-indigo-600">{{ $doctor->specialty->name }}</p>
                        <p>{{ $doctor->address }} {{ $doctor->zip_code }} {{ $doctor->city }}</p>
                    </div>
                </div>

                <!-- Slots Selection -->
                @if($slots->count() > 0)
                    <div class="space-y-6">
                        @foreach($slots as $day => $daySlots)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 capitalize">{{ $day }}</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                    @foreach($daySlots as $slot)
                                        <form method="POST" action="{{ route('booking.store') }}">
                                            @csrf
                                            <input type="hidden" name="slot_id" value="{{ $slot->id }}">
                                            <button type="submit" 
                                                    class="w-full text-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                                    style="background-color: #1f2937; color: white;"
                                                    onclick="return confirm('Confirmer le rendez-vous pour le {{ $day }} à {{ $slot->start_time->format('H:i') }} ?')">
                                                {{ $slot->start_time->format('H:i') }}
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">Aucune disponibilité pour le moment.</p>
                        <a href="{{ route('doctor.search') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Rechercher un autre médecin</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>
