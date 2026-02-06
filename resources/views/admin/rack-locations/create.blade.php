<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rack Location - Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">3PL Admin Dashboard</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
                <a href="{{ route('admin.clients.index') }}" class="hover:underline">Clients</a>
                <a href="{{ route('admin.agents.index') }}" class="hover:underline">Agents</a>
                <a href="{{ route('admin.admins.index') }}" class="hover:underline">Admins</a>
                <a href="{{ route('admin.rack-locations.index') }}" class="underline font-semibold">Rack Locations</a>
                <a href="{{ route('admin.settlements.index') }}" class="hover:underline">Settlements</a>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:underline">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="{{ route('admin.rack-locations.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Back to Rack Locations
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Rack Location</h2>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.rack-locations.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Location Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="code" 
                               name="code" 
                               value="{{ old('code') }}"
                               placeholder="e.g., A1-01 or D5-20"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Format: Zone-Aisle-Rack (e.g., A1-01)</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Zone -->
                    <div>
                        <label for="zone" class="block text-sm font-medium text-gray-700 mb-2">
                            Zone <span class="text-red-500">*</span>
                        </label>
                        <select id="zone" 
                                name="zone" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Select Zone</option>
                            <option value="A" {{ old('zone') == 'A' ? 'selected' : '' }}>Zone A</option>
                            <option value="B" {{ old('zone') == 'B' ? 'selected' : '' }}>Zone B</option>
                            <option value="C" {{ old('zone') == 'C' ? 'selected' : '' }}>Zone C</option>
                            <option value="D" {{ old('zone') == 'D' ? 'selected' : '' }}>Zone D</option>
                            <option value="E" {{ old('zone') == 'E' ? 'selected' : '' }}>Zone E</option>
                            <option value="F" {{ old('zone') == 'F' ? 'selected' : '' }}>Zone F</option>
                        </select>
                        @error('zone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Aisle -->
                    <div>
                        <label for="aisle" class="block text-sm font-medium text-gray-700 mb-2">
                            Aisle <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="aisle" 
                               name="aisle" 
                               value="{{ old('aisle') }}"
                               min="1"
                               max="99"
                               placeholder="e.g., 1 or 10"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('aisle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rack -->
                    <div>
                        <label for="rack" class="block text-sm font-medium text-gray-700 mb-2">
                            Rack <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="rack" 
                               name="rack" 
                               value="{{ old('rack') }}"
                               min="1"
                               max="99"
                               placeholder="e.g., 1 or 20"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('rack')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Tip:</strong> Location codes should be unique and follow a consistent naming convention. 
                            New locations are typically set as 'Available' until a shipment is assigned.
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            Create Rack Location
                        </button>
                        <a href="{{ route('admin.rack-locations.index') }}" 
                           class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-semibold text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
