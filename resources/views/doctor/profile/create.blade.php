<x-guest-layout>
    <div class="max-w-xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900">Créer le profil médecin</h1>
            <p class="text-sm text-gray-600 mt-1">
                Renseigne les informations du médecin.
            </p>
        </div>

        <form method="POST" action="{{ route('doctor.profile.store') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="address" value="Adresse du cabinet" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                              value="{{ old('address') }}" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="city" value="Ville" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                              value="{{ old('city') }}" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="zip_code" value="Code Postal" />
                <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full"
                              value="{{ old('zip_code') }}" />
                <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="specialty_id" value="Spécialité" />
                <select id="specialty_id" name="specialty_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="" disabled {{ old('specialty_id') ? '' : 'selected' }}>
                        Choisir une spécialité
                    </option>

                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" {{ (string)old('specialty_id') === (string)$specialty->id ? 'selected' : '' }}>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('specialty_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" value="Téléphone" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                              value="{{ old('phone') }}" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="bio" value="Présentation" />
                <textarea id="bio" name="bio" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio') }}</textarea>
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
