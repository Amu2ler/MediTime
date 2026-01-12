<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Rôle')" />
            <select name="role" id="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">-- Choisir un rôle --</option>
                <option value="patient" {{ old('role') === 'patient' ? 'selected' : '' }}>Patient</option>
                <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>Médecin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Select spécialité (caché par défaut) -->
        <div class="mt-4 hidden" id="specialty-div">
            <x-input-label for="specialty_id" value="Spécialité" />
            <select name="specialty_id" id="specialty_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">-- Choisir une spécialité --</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('specialty_id')" class="mt-2" />
        </div>

        <!-- Upload Attestation (caché par défaut) -->
        <div class="mt-4 hidden" id="certificate-div">
            <x-input-label for="certificate" value="Attestation de médecin (PDF, JPG, PNG)" />
            <input type="file" name="certificate" id="certificate" class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100
            " />
            <x-input-error :messages="$errors->get('certificate')" class="mt-2" />
        </div>

        <script>
            const roleSelect = document.getElementById('role');
            const specialtyDiv = document.getElementById('specialty-div');
            const certificateDiv = document.getElementById('certificate-div');

            function toggleDoctorFields() {
                const isDoctor = roleSelect.value === 'doctor';
                specialtyDiv.classList.toggle('hidden', !isDoctor);
                certificateDiv.classList.toggle('hidden', !isDoctor);
            }

            roleSelect.addEventListener('change', toggleDoctorFields);
            
            // Initial check on page load
            toggleDoctorFields();
        </script>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
