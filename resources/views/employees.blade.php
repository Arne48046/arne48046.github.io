<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4" style="float: right;">
                        <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Add Employee</a>
                    </div>
                  
                    <table class="table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Worked Hours</th>
                                <th class="px-4 py-2">Active Worked Hours</th>
                                <th class="px-4 py-2">Inactive Worked Hours</th>
                                <th class="px-4 py-2">Actions</th>        
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td class="border px-4 py-2">{{ $employee->name }}</td>
                                    <td class="border px-4 py-2">{{ $employee->email }}</td>
                                    <td class="border px-4 py-2">{{ $employee->worked_hours }}</td>
                                    <td class="border px-4 py-2">{{ $employee->active_worked_hours }}</td>
                                    <td class="border px-4 py-2">{{ $employee->inactive_worked_hours }}</td>

                                    
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('employee.show', $employee->user_id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
