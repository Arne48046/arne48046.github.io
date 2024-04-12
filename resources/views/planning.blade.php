<!-- planning.blade.php -->
<style>
    .selected-cell {
    background-color: purple;
    }
    .dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: purple;
    border-radius: 50%;
    margin-left: 2px; /* Adjust as needed */

}

</style>
<x-app-layout>
    <!-- Display current date -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-left mt-4">
                <p><strong>{{ date('j F Y') }}</strong></p>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <button id="prevWeek" onclick="navigate('prev')"> <- Vorige Week </button>
                    <h3 id="currentWeek"></h3>
                    <button id="nextWeek" onclick="navigate('next')"> Volgende Week -> </button>
                </div>
            </div>

            <form method="POST" action="{{ route('planning.store') }}">
    @csrf
    <label for="employeeOccupation" class="block text-sm font-medium text-gray-700">Medewerker bezetting weergeven:</label>
    <select id="employeeOccupation" name="employeeOccupation" onchange="getEmployeeWorkingHours(this.value)"
        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    <option value="geen" selected>Geen</option>
    @foreach($employees as $employee)
    <option value="{{ $employee->user_id }}">{{ $employee->name }}</option>
    @endforeach
</select>


    <table id="scheduleTable" class="mt-8 w-full">
        <!-- Table content will be generated dynamically -->
    </table>

    <div id="selectedDateTime" class="mt-4"></div>

    <div class="flex">
        <div class="w-1/2 pr-4">
            <!-- Left side of the form -->
            <label for="employeeSelect" class="block text-sm font-medium text-gray-700">Select Employee:</label>
            <select id="employeeSelect" name="employeeSelect"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <!-- Populate this dropdown menu with employee options -->
                @foreach($employees as $employee)
                <option value="{{ $employee->user_id }}">{{ $employee->name }}</option>
                @endforeach
            </select>

            <label for="date" class="block text-sm font-medium text-gray-700">Datum:</label>
            <input type="date" id="date" name="date"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                <label for="start_time" class="block text-sm font-medium text-gray-700">Vanaf:</label>
            <input type="time" id="start_time" name="start_time"
                class="form-control mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <label for="end_time" class="block text-sm font-medium text-gray-700">Tot:</label>
            <input type="time" id="end_time" name="end_time"
                class="form-control mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <p id="timeValidationMessage" style="color: red; display: none;">Incorrect tijd format</p>

<script>
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const validationMessage = document.getElementById('timeValidationMessage');

    // Function to validate the time inputs
    function validateTimeInputs() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (startTime && endTime) {
            const startDateTime = new Date(`2000-01-01T${startTime}`);
            const endDateTime = new Date(`2000-01-01T${endTime}`);

            // Compare the time values
            if (endDateTime <= startDateTime) {
                validationMessage.style.display = 'block';
                endTimeInput.setCustomValidity('Invalid time');
            } else {
                validationMessage.style.display = 'none';
                endTimeInput.setCustomValidity('');
            }
        }
    }

    // Event listeners to trigger validation when the input fields change
    startTimeInput.addEventListener('change', validateTimeInputs);
    endTimeInput.addEventListener('change', validateTimeInputs);
</script>

            <!-- Hidden input field to pass employee name -->
            <input type="hidden" id="employee_name" name="employee_name">

            <button type="submit"
                class="bg-purple-800 mt-4 inline-block px-6 py-3 text-white hover:bg-purple-600 rounded-lg border-none shadow-lg dark:text-gray-200">
                <span class="text-lg font-semibold">Medewerker inplannen</span>
            </button>
        </div>

        <!-- Right side content -->

        <div class="w-1/2 pl-4 mx-3">
            <div id="selectedCellData" class="text-white">

            </div>
        </div>
    </div>
</form>

        </div>
    </div>
</div>
</x-app-layout>

<script>
    document.getElementById('employeeSelect').addEventListener('change', function() {
    // Get the selected employee's name
    var selectedEmployeeName = this.options[this.selectedIndex].text;

    // Update the value of the hidden input field
    document.getElementById('employee_name').value = selectedEmployeeName;
});

    function navigate(direction) {
        var currentDate = document.getElementById('currentWeek').dataset.date;
        var date = new Date(currentDate);

        if (direction === 'prev') {
            date.setDate(date.getDate() - 7);
        } else if (direction === 'next') {
            date.setDate(date.getDate() + 7);
        }

        updateWeek(date);
        
    }

    function selectCell(cell, dateTime, startTime, dayDate) {
    var selectedCells = document.querySelectorAll('.selected-cell');
    selectedCells.forEach(function(selectedCell) {
        selectedCell.classList.remove('selected-cell');
    });
    cell.classList.add('selected-cell');

    // Split the date and time from the dateTime parameter
    const [dayName, date] = dateTime.split(', ');

    // Set the value of the date input to the selected cell's date
    document.getElementById('date').value = dayDate;

    // Set the value of the start time input to the selected cell's start time
    document.getElementById('start_time').value = startTime;

    // Make an AJAX request to the endpoint with the selected time
    const url = `http://127.0.0.1:8000/get-employees-working-at-time?time=${dayDate}%09${startTime}`;

    console.log(url);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Handle the response data (list of employees)
            displayEmployees(data);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}






function displayEmployees(employees) {
    // Clear the existing content of the selectedCellData div
    const selectedCellData = document.getElementById('selectedCellData');
    selectedCellData.innerHTML = '';

    // Create a table to display employee information
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped'); // Add Bootstrap table classes if needed

    // Create table headers
    const headers = ['ID', 'Name', 'Start Time', 'End Time', 'Actions'];
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Create table body
    const tbody = document.createElement('tbody');
    employees.forEach(employee => {
        const tr = document.createElement('tr');
        const { id, name, start_time, end_time } = employee;
    
        tr.innerHTML = `
            <td>${id}</td>
            <td>${name}</td>
            <td>${start_time}</td>
            <td>${end_time.replace(/\d+:\d+:\d+/, match => match.split(':').map(Number)[1] >= 30 ? `${match.split(':').map(Number)[0]+1}:00:00` : `${match.split(':').map(Number)[0]}:00:00`)}</td>
            <td><form method="POST" action="/employee/worktime/${id}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form></td>
        `;
        tbody.appendChild(tr);
    });
    table.appendChild(tbody);

    // Append the table to the selectedCellData div
    selectedCellData.appendChild(table);
}


function getEmployeeWorkingHours(employeeId) {
    const url = `http://127.0.0.1:8000/get-employee-working-hours/${employeeId}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            viewOccupation(data);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}


function viewOccupation(workingHours) {
    // Remove existing dots from all cells
    const existingDots = document.querySelectorAll('.dot');
    existingDots.forEach(dot => dot.remove());

    // Iterate over each working hour
    workingHours.forEach(hour => {
        const startTime = hour.start_time.split(' ')[1]; // Extracting time from the start_time
        const endTime = hour.end_time.split(' ')[1]; // Extracting time from the end_time
        const dayDate = hour.start_time.split(' ')[0]; // Extracting date from the start_time

        // Find all cells in the table
        const cells = document.querySelectorAll('td');

        // Loop through each cell
        cells.forEach(cell => {
            const cellTime = cell.dataset.time; // Get the time from data-time attribute
            const cellDay = cell.dataset.day; // Get the date from data-day attribute

            // Check if the cell's time and date fall within the working hour's time range and date
            if (cellDay === dayDate && cellTime >= startTime && cellTime < endTime) {
                // Add a dot to the cell
                const dot = document.createElement('div');
                dot.classList.add('dot');
                cell.appendChild(dot);
            }
        });
    });
}






function updateWeek(date) {
    var startOfWeek = new Date(date);
    startOfWeek.setDate(date.getDate() - date.getDay());
    var endOfWeek = new Date(date);
    endOfWeek.setDate(date.getDate() - date.getDay() + 6);

    var currentWeekText =
        'Week ' +
        getWeekNumber(startOfWeek) +
        ', ' +
        startOfWeek.toLocaleDateString('en-US', { weekday: 'long' }) +
        ' - ' +
        endOfWeek.toLocaleDateString('en-US', { weekday: 'long' });

    function getWeekNumber(date) {
        var target = new Date(date.valueOf());
        var dayNr = (date.getDay() + 6) % 7;
        target.setDate(target.getDate() - dayNr + 3);
        var firstThursday = target.valueOf();
        target.setMonth(0, 1);
        if (target.getDay() !== 4) {
            target.setMonth(0, 1 + ((4 - target.getDay()) + 7) % 7);
        }
        return 1 + Math.ceil((firstThursday - target) / 604800000);
    }

    document.getElementById('currentWeek').innerText = currentWeekText;
    document.getElementById('currentWeek').dataset.date = startOfWeek.toISOString().split('T')[0]; // Change here

    // Now you can dynamically generate the table headers and rows here based on the current week
    var tableHeadersRow = document.createElement('tr');
    tableHeadersRow.innerHTML = `
            <th class="border px-4 py-6" style="width: 150px;"></th>
            <th class="border px-4 py-2 italic">9:00-10:00</th>
            <th class="border px-4 py-2 italic">10:00-11:00</th>
            <th class="border px-4 py-2 italic">11:00-12:00</th>
            <th class="border px-4 py-2 italic">12:00-13:00</th>
            <th class="border px-4 py-2 italic">13:00-14:00</th>
            <th class="border px-4 py-2 italic">14:00-15:00</th>
            <th class="border px-4 py-2 italic">15:00-16:00</th>
            <th class="border px-4 py-2 italic">16:00-17:00</th>
            <th class="border px-4 py-2 italic">17:00-18:00</th>
            <th class="border px-4 py-2 italic">18:00-19:00</th>
            <th class="border px-4 py-2 italic">19:00-20:00</th>
        `;
    document.getElementById('scheduleTable').innerHTML = '';
    document.getElementById('scheduleTable').appendChild(tableHeadersRow);

    var tbody = document.createElement('tbody');
    for (var i = 0; i < 7; i++) {
        var currentDay = new Date(startOfWeek);
        currentDay.setDate(startOfWeek.getDate() + i);

        var tableRow = document.createElement('tr');
        var dayName = currentDay.toLocaleDateString('en-US', { weekday: 'short' });
        var dayDate = currentDay.toISOString().split('T')[0];
        tableRow.innerHTML = `
        <td class="border px-4 py-2 pl-2">${dayName} ${currentDay.getDate()}</td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="09:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '09:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="10:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '10:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="11:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '11:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="12:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '12:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="13:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '13:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="14:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '14:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="15:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '15:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="16:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '16:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="17:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '17:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="18:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '18:00:00', '${dayDate}')"></td>
            <td class="border px-4 py-2" data-day="${dayDate}" data-time="19:00:00" onclick="selectCell(this, '${dayName} ${currentDay.getDate()}', '19:00:00', '${dayDate}')"></td>
            `;
        tbody.appendChild(tableRow);
    }
    document.getElementById('scheduleTable').appendChild(tbody);

    // Get the selected employee's ID
    var selectedEmployeeId = document.getElementById('employeeOccupation').value;

    // Call getEmployeeWorkingHours to update the occupation view
    getEmployeeWorkingHours(selectedEmployeeId);
}

    // Initial call to update the week
    document.addEventListener('DOMContentLoaded', function() {
        updateWeek(new Date());
    });

    function resetMin(e) {
        e.addEventListener("change", function() {
            e.value = e.value.split(":")[0] + ":00";
        });
    }

    resetMin(document.getElementsByClassName("form-control")[0]);
    resetMin(document.getElementsByClassName("form-control")[1]);
</script>
