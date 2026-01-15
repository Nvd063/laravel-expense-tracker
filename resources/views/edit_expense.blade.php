<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Edit Expense
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-700">
                
                <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-medium text-sm text-gray-300">Description</label>
                        <input type="text" name="description" value="{{ $expense->description }}" 
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 rounded-md shadow-sm w-full mt-1 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-300">Amount (Rs)</label>
                        <input type="number" name="amount" step="0.01" value="{{ $expense->amount }}" 
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 rounded-md shadow-sm w-full mt-1 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-300">Category</label>
                        <select name="category_id" 
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 rounded-md shadow-sm w-full mt-1 px-3 py-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $expense->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-300">Date</label>
                        <input type="date" name="date" value="{{ $expense->date }}" 
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 rounded-md shadow-sm w-full mt-1 px-3 py-2" required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">Cancel</a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                            Update Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>