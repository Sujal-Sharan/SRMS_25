
const students = [
    { name: "John Doe", roll: "123456", working_days: "28",attendance: {} },
    { name: "Jane Smith", roll: "123457",  working_days: "28",attendance: {} },
    { name: "Alex Johnson", roll: "123458", working_days: "28", attendance: {} }
];

function loadAttendanceTable() {
    const table = document.getElementById("attendanceTable");
    table.innerHTML = ""; // Clear previous data

    students.forEach(student => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${student.name}</td>
            <td>${student.roll}</td>
            <td><input type="number" min="0" max="31" value="0" class="present"></td>
             <td>${student.working_days}</td>
             <td>${student.percentage}</td>
        `;
        table.appendChild(row);
    });
}

function submitAttendance() {
    const selectedMonth = document.getElementById("month").value;
    const rows = document.querySelectorAll("#attendanceTable tr");

    rows.forEach((row, index) => {
        const presentDays = parseInt(row.querySelector(".present").value) || 0;
        const totalWorkingDays = parseInt(students[index].working_days) || 0;

        const attendancePercentage = totalWorkingDays > 0 ? (presentDays / totalWorkingDays) * 100 : 0;

        students[index].attendance[selectedMonth] = {
            Present: presentDays,
            Total_Working_Days: totalWorkingDays,
            Percentage: attendancePercentage.toFixed(2) + "%"
        };
        row.cells[4].textContent = attendancePercentage.toFixed(2) + "%";

    });

    console.log("Updated Attendance Data:", students);
    alert("Attendance saved successfully!");
}

window.onload = loadAttendanceTable;
