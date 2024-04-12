<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-4">Medewerker overzicht</h1>

                    <div class="mb-4">
                        <label for="user_id" class="block font-semibold">Gebruikers-ID:</label>
                        <span>{{ $employee->user_id }}</span>
                    </div>
                    <form method="POST" action="{{ route('employee.update', $employee->user_id) }}" onsubmit="return confirm('Weet je zeker dat je deze medewerker wilt bijwerken?')">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block font-semibold">Naam:</label>
                            <input type="text" id="name" name="name" value="{{ $employee->name }}" class="border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block font-semibold">E-mail:</label>
                            <input type="email" id="email" name="email" value="{{ $employee->email }}" class="border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md">
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">Bijwerken</button>
                    </form>



                    <div class="mb-4 flex flex-col sm:flex-row">
                        <!-- Adjust layout for small screens -->
                        <label for="month" class="mr-2">Selecteer maand:</label>
                        <form method="GET" action="{{ route('employee.show', $employee->user_id) }}">
                            <select id="month" name="month" onchange="this.form.submit()"
                                class="bg-white dark:bg-gray-700 dark:text-white rounded-md border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-200 mb-2 sm:mb-0 sm:mr-2">
                                <!-- Adjust spacing for small screens -->
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" @if($selectedMonth==$i) selected
                                    @endif>
                                    {{ Carbon\Carbon::create(null, $i, 1)->format('F') }}</option>
                                    @endfor
                            </select>
                        </form>
                    </div>
                    <h2 class="text-xl font-bold mt-4">Gewerkte uren voor
                        {{ Carbon\Carbon::create(null, $selectedMonth, 1)->format('F') }}</h2>
                    <div class="overflow-x-auto">
                        <!-- Add horizontal scrolling for small screens -->
                        <table class="table-auto">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Starttijd</th>
                                    <th class="px-4 py-2">Eindtijd</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <p>Afgelopen werktijden:</p>
                                    </td>
                                </tr>
                                @php
                                $totalWorkedHours = 0; // Initialiseer de variabele voor totaal gewerkte uren
                                @endphp
                                @foreach($workedHours as $workedHour)
                                @if(\Carbon\Carbon::parse($workedHour->end_time)->isPast())
                                @php
                                // Bereken de duur tussen start- en eindtijden
                                $startTime = \Carbon\Carbon::parse($workedHour->start_time);
                                $endTime = \Carbon\Carbon::parse($workedHour->end_time);
                                $workedHoursDuration = $startTime->diffInHours($endTime);

                                // Tel de duur op bij het totaal aantal gewerkte uren
                                $totalWorkedHours += $workedHoursDuration;
                                @endphp
                                <tr>
                                    <td class="border px-4 py-2">
                                        {{ \Carbon\Carbon::parse($startTime)->roundHour()->format('Y-m-d H:i:s') }}</td>
                                    <td class="border px-4 py-2">
                                        {{ \Carbon\Carbon::parse($endTime)->roundHour()->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td colspan="2">Gewerkte uren: {{ round($totalWorkedHours, 1) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p>Toekomstige werktijden:</p>
                                    </td>
                                </tr>
                                @foreach($workedHours as $workedHour)
                                @if(\Carbon\Carbon::parse($workedHour->end_time)->isFuture())
                                <tr>
                                    <td class="border px-4 py-2">
                                        {{ \Carbon\Carbon::parse($workedHour->start_time)->format('Y-m-d H:i:s') }}</td>
                                    <td class="border px-4 py-2">
                                        {{ \Carbon\Carbon::parse($workedHour->end_time)->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Verwijder formulier -->
                    <form method="POST" action="{{ route('employee.destroy', $employee->user_id) }}"
                        onsubmit="return confirm('Weet je zeker dat je deze medewerker wilt verwijderen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-700 font-bold py-2 px-4 rounded">Verwijderen</button>
                    </form>
             
            </div>
        </div>
    </div>
</x-app-layout>