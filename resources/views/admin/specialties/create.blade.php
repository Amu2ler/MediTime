<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Ajouter une spécialité
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Créez une nouvelle spécialité médicale.
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('specialties.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Nom de la spécialité
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Ex : Dermatologie"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                           focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a
                    href="{{ route('specialties.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 underline"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent
                           rounded-md font-semibold text-sm text-gray-600 hover:bg-indigo-700
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Créer la spécialité
                </button>
            </div>
        </form>
        </div>
    </div>
</x-app-layout>
