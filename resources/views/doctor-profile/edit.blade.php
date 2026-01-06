<x-guest-layout>
    <div class="max-w-xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900">Modifier le profil médecin</h1>
            <p class="text-sm text-gray-600 mt-1">
                Mets à jour les informations du médecin.
            </p>
        </div>

        <form method="POST" action="{{ route('doctor.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="first_name" value="Prénom" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                              value="{{ old('first_name', $profile->first_name) }}" required autofocus />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" value="Nom" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                              value="{{ old('last_name', $profile->last_name) }}" required />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="specialty_id" value="Spécialité" />
                <select id="specialty_id" name="specialty_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="" disabled>Choisir une spécialité</option>

                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}"
                            {{ (string)old('specialty_id', $profile->specialty_id) === (string)$specialty->id ? 'selected' : '' }}>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('specialty_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" value="Téléphone" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                              value="{{ old('phone', $profile->phone) }}" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="bio" value="Présentation" />
                <textarea id="bio" name="bio" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $profile->bio) }}</textarea>
                <x-input-error :messages="$errors->get('bio')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Retour
                </a>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
