<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations du praticien') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Mettez à jour vos informations professionnelles (spécialité, adresse, bio).") }}
        </p>
    </header>

    <form method="POST" action="{{ route('doctor.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Specialty (Note: Usually read-only or handled carefully, but allowing edit here as per previous logic) -->
        <div>
            <x-input-label for="specialty_id" :value="__('Spécialité')" />
            <select id="specialty_id" name="specialty_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach(\App\Models\Specialty::orderBy('name')->get() as $specialty)
                    <option value="{{ $specialty->id }}"
                        {{ old('specialty_id', auth()->user()->doctorProfile?->specialty_id) == $specialty->id ? 'selected' : '' }}>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('specialty_id')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Adresse du cabinet')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', auth()->user()->doctorProfile?->address)" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="zip_code" :value="__('Code Postal')" />
                <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code', auth()->user()->doctorProfile?->zip_code)" />
                <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
            </div>
            <div>
                <x-input-label for="city" :value="__('Ville')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', auth()->user()->doctorProfile?->city)" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>
        </div>

        <div>
            <x-input-label for="phone" :value="__('Téléphone')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', auth()->user()->doctorProfile?->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="bio" :value="__('Bio / Présentation')" />
            <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', auth()->user()->doctorProfile?->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'doctor-profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>
