
<?php
session_start();

// Initialize appointments in the session
if (!isset($_SESSION['appointments'])) {
    $_SESSION['appointments'] = [];
}

// Handle form submissions for adding, editing, and deleting appointments
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $dateKey = "$year-$month-$day";

    if ($action === 'add') {
        $timeSlot = $_POST['timeSlot'];
        $appointment = $_POST['appointment'];
        $_SESSION['appointments'][$dateKey][] = [
            'timeSlot' => $timeSlot,
            'appointment' => $appointment,
        ];
    } elseif ($action === 'delete') {
        $index = $_POST['index'];
        unset($_SESSION['appointments'][$dateKey][$index]);
        $_SESSION['appointments'][$dateKey] = array_values($_SESSION['appointments'][$dateKey]);
    } elseif ($action === 'edit') {
        $index = $_POST['index'];
        $timeSlot = $_POST['timeSlot'];
        $appointment = $_POST['appointment'];
        $_SESSION['appointments'][$dateKey][$index] = [
            'timeSlot' => $timeSlot,
            'appointment' => $appointment,
        ];
    }

    exit;
}

// Get the current month and year
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfWeek = date('w', strtotime("$year-$month-01"));
$daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Appointment Calendar</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/calander.css">
</head>
<body>
    <div class="calendar-container">
        <div class="calendar-header">
            <button onclick="navigate(-1)">&lt; Prev</button>
            <select id="monthSelect" onchange="changeMonth()">
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    $selected = $i == $month ? "selected" : "";
                    echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                }
                ?>
            </select>
            <select id="yearSelect" onchange="changeYear()">
                <?php
                for ($i = $year - 5; $i <= $year + 5; $i++) {
                    $selected = $i == $year ? "selected" : "";
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
            <button onclick="navigate(1)">Next &gt;</button>
        </div>
        <div class="calendar">
            <?php
                // Render day headers
                foreach ($daysOfWeek as $day) {
                    echo "<div class='day-header'>$day</div>";
                }

                // Blank spaces for alignment
                for ($i = 0; $i < $firstDayOfWeek; $i++) {
                    echo "<div class='day blank'></div>";
                }

                // Render days with appointments
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dateKey = "$year-$month-$day";
                    echo "<div class='day' data-date='$dateKey'>
                            <div class='day-number'>$day</div>
                            <div class='appointments'>";
                    
                    if (isset($_SESSION['appointments'][$dateKey])) {
                        foreach ($_SESSION['appointments'][$dateKey] as $index => $appointment) {
                            echo "<div class='appointment' onclick=\"viewAppointment('$dateKey', $index)\">
                                    {$appointment['timeSlot']} - {$appointment['appointment']}
                                  </div>";
                        }
                    }
                    
                    echo "</div>
                          <button class='add-appointment' onclick=\"openAddModal('$day')\">Add</button>
                          </div>";
                }
            ?>
        </div>
    </div>

    <!-- Add Appointment Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h2>Add Appointment</h2>
            <form id="addForm">
                <input type="hidden" id="addDay" name="day">
                <input type="hidden" id="addMonth" value="<?php echo $month; ?>">
                <input type="hidden" id="addYear" value="<?php echo $year; ?>">
                <label for="timeSlot">Time Slot:</label>
                <input type="time" id="timeSlot" name="timeSlot" required>
                <label for="appointment">Details:</label>
                <input type="text" id="appointment" name="appointment" required>
                <button type="button" onclick="saveAppointment()">Save</button>
            </form>
        </div>
    </div>

    <!-- Appointment Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('detailsModal')">&times;</span>
            <h2>Appointment Details</h2>
            <p><strong>Date:</strong> <span id="detailsDate"></span></p>
            <p><strong>Time:</strong> <span id="detailsTime"></span></p>
            <p><strong>Details:</strong> <span id="detailsText"></span></p>
            <button id="editButton">Edit</button>
            <button id="deleteButton">Delete</button>
        </div>
    </div>

    <script>
        function navigate(offset) {
            const currentMonth = parseInt(document.getElementById("monthSelect").value);
            const currentYear = parseInt(document.getElementById("yearSelect").value);

            let newMonth = currentMonth + offset;
            let newYear = currentYear;

            if (newMonth < 1) {
                newMonth = 12;
                newYear -= 1;
            } else if (newMonth > 12) {
                newMonth = 1;
                newYear += 1;
            }

            window.location.href = `calendar.php?month=${newMonth}&year=${newYear}`;
        }

        function changeMonth() {
            const month = document.getElementById("monthSelect").value;
            const year = document.getElementById("yearSelect").value;
            window.location.href = `calendar.php?month=${month}&year=${year}`;
        }

        function changeYear() {
            changeMonth();
        }

        function openAddModal(day) {
            document.getElementById("addDay").value = day;
            document.getElementById("addModal").style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        function saveAppointment() {
            const form = document.getElementById("addForm");
            const formData = new FormData(form);
            formData.append("action", "add");

            fetch("calendar.php", { method: "POST", body: formData }).then(() => {
                window.location.reload();
            });
        }

        function viewAppointment(dateKey, index) {
            const appointment = <?php echo json_encode($_SESSION['appointments']); ?>[dateKey][index];
            document.getElementById("detailsDate").innerText = dateKey;
            document.getElementById("detailsTime").innerText = appointment.timeSlot;
            document.getElementById("detailsText").innerText = appointment.appointment;
            document.getElementById("detailsModal").style.display = "block";

            document.getElementById("deleteButton").onclick = () => deleteAppointment(dateKey, index);
            document.getElementById("editButton").onclick = () => editAppointment(dateKey, index);
        }

        function deleteAppointment(dateKey, index) {
            const formData = new FormData();
            formData.append("action", "delete");
            formData.append("day", dateKey.split("-")[2]);
            formData.append("month", dateKey.split("-")[1]);
            formData.append("year", dateKey.split("-")[0]);
            formData.append("index", index);

            fetch("calendar.php", { method: "POST", body: formData }).then(() => {
                window.location.reload();
            });
        }

        function editAppointment(dateKey, index) {
            // Code for editing appointments goes here
        }
    </script>
</body>
</html>

