<x-app-layout>
<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Package Installer</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

    <h2>zip upload</h2>
        <form wire:submit.prevent="uploadAndInstall" class="space-y-4">
            <div>
                <label for="zipFile" class="block text-sm font-medium text-gray-700">Upload Package (ZIP)</label>
                <input type="file" id="zipFile" wire:model="zipFile" class="mt-1 block w-full">
                @error('zipFile') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Upload and Install
            </button>
        </form>
    <h2>Git download</h2>
    <div>
        <label class="block mt-2">GitHub Repository URL:</label>
        <input type="text" wire:model="packageUrl" placeholder="https://github.com/vendor/package.git" class="w-full p-2 border rounded my-1">
        <button wire:click="clonePackage" class="bg-blue-500 text-white p-2 rounded mt-4" wire:loading.attr="disabled">
            Clone Package
        </button>
    </div>
    
    
        <hr class="my-6">

        <h2 class="text-xl font-bold mb-4">Installed Packages</h2>
        
  

    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <!-- Header Section -->
        <h1 class="text-2xl font-bold mb-6 text-center">Package Installer</h1>

        <!-- Success Notification -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($packages as $package)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Package Icon -->
                    <div class="p-4 bg-gray-100 text-center">
                        <img src="{{ asset('custom/'.$package->name.'/imgs/icons/icon.svg') }}" alt="{{ $package->name }} Icon" class="w-20 h-20 mx-auto rounded-full object-cover">
                        
                    </div>

                    <!-- Package Details -->
                    <div class="p-4 space-y-2">
                        <h3 class="text-lg font-semibold">{{ $package->name }}</h3>

                        <!-- Status -->
                        <p class="text-sm text-gray-500">
                            @if ($package->status == 0)
                                <span class="text-red-500">Not Installed</span>
                            @elseif ($package->status == 1)
                                <span class="text-yellow-500">Not Activated</span>
                            @else
                                <span class="text-green-500">Active</span>
                            @endif
                        </p>

                        <!-- Action Button -->
                        <div class="flex justify-end">
                            @if ($package->status == 0)
                                <button wire:click="installPackage({{ $package->id }})" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                                    Install
                                </button>
                            @elseif ($package->status == 1)
                                <button wire:click="activatePackage({{ $package->id }})" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-200">
                                    Activate
                                </button>
                            @else
                                <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-200">
                                    Deactivate
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 italic">
                    No packages available.
                </div>
            @endforelse
        </div>
    </div>
</div>


</x-app-layout>

