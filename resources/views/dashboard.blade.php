<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('My Expense Tracker') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center items-center border border-gray-700">
                    <h3 class="text-lg font-medium text-gray-400">Total Spent</h3>
                    <p class="text-5xl font-bold text-red-400 mt-2">Rs. {{ number_format($total, 2) }}</p>
                </div>

                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex justify-center items-center border border-gray-700" style="height: 300px;">
                    @if($total > 0)
                        <canvas id="expenseChart"></canvas>
                    @else
                        <p class="text-gray-400">No data to show chart</p>
                    @endif
                </div>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-700">
                <h3 class="text-lg font-bold mb-4 text-gray-200">Add New Expense</h3>

                <form action="{{ route('expenses.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-300">Description</label>
                        <input type="text" name="description" placeholder="e.g. Burger, Petrol"
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 placeholder-gray-400 rounded-md shadow-sm w-full mt-1 px-3 py-2"
                            required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-300">Amount (Rs)</label>
                        <input type="number" name="amount" step="0.01" placeholder="0.00"
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 placeholder-gray-400 rounded-md shadow-sm w-full mt-1 px-3 py-2"
                            required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-300">Category</label>
                        <select name="category_id"
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 rounded-md shadow-sm w-full mt-1 px-3 py-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-300">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                            class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 rounded-md shadow-sm w-full mt-1 px-3 py-2"
                            required>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full transition-colors duration-200">
                            + Add Expense
                        </button>
                    </div>
                </form>
            </div>
            <div class="mb-4">
                <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search expense (e.g. Burger)..."
                        class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 placeholder-gray-400 rounded-md shadow-sm w-full md:w-1/3 px-3 py-2">

                    <select name="filter_category"
                        class="bg-gray-700 border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-200 rounded-md shadow-sm w-full md:w-1/4 px-3 py-2">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('filter_category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        Search
                    </button>

                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-600 hover:bg-gray-500 text-gray-200 font-bold py-2 px-4 rounded transition-colors duration-200">
                        Reset
                    </a>
                </form>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-700">
                <h3 class="text-lg font-bold mb-4 text-gray-200">History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="p-2 text-gray-300">Date</th>
                                <th class="p-2 text-gray-300">Description</th>
                                <th class="p-2 text-gray-300">Category</th>
                                <th class="p-2 text-right text-gray-300">Amount</th>
                                <th class="p-2 text-gray-300">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr class="border-b border-gray-700 hover:bg-gray-700 transition-colors duration-200">
                                    <td class="p-2 text-gray-400 text-sm">
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                                    </td>
                                    <td class="p-2 font-medium text-gray-200">{{ $expense->description }}</td>
                                    <td class="p-2">
                                        <span class="bg-gray-700 text-gray-300 py-1 px-3 rounded-full text-xs">
                                            {{ $expense->category->name }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-right font-bold text-red-400">- Rs.
                                        {{ number_format($expense->amount) }}
                                    </td>
                                    <td class="p-2 flex gap-2">
                                        <a href="{{ route('expenses.edit', $expense->id) }}"
                                            class="text-yellow-400 hover:text-yellow-300 font-bold transition-colors duration-200">
                                            Edit
                                        </a>

                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-300 font-bold transition-colors duration-200">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($expenses->isEmpty())
                    <p class="text-center text-gray-400 mt-4">No expenses found. Start adding!</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('expenseChart');

    new Chart(ctx, {
        type: 'doughnut', // 'pie' bhi kar sakte hain
        data: {
            labels: {!! json_encode($labels) !!}, // PHP array ko JS mein convert kiya
            datasets: [{
                label: 'Expense by Category',
                data: {!! json_encode($data) !!},
                borderWidth: 1,
                backgroundColor: [
                    '#FF6384', // Red
                    '#36A2EB', // Blue
                    '#FFCE56', // Yellow
                    '#4BC0C0', // Teal
                    '#9966FF', // Purple
                    '#FF9F40'  // Orange
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'white',
                    borderWidth: 1
                }
            }
        }
    });
</script>