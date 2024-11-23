<?php
// Sample array for demonstration purposes (replace with database query)
$newAppointments = [
    ['AppointmentNo' => 'A201', 'TimeSlot' => '10:00 AM - 11:00 AM', 'Date' => '2024-11-26'],
    ['AppointmentNo' => 'A202', 'TimeSlot' => '1:00 PM - 2:00 PM', 'Date' => '2024-11-27'],
    ['AppointmentNo' => 'A203', 'TimeSlot' => '3:00 PM - 4:00 PM', 'Date' => '2024-11-28'],
    ['AppointmentNo' => 'A204', 'TimeSlot' => '5:00 PM - 6:00 PM', 'Date' => '2024-11-29'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointments</title>
    <link rel="stylesheet" href="appointments.css">
</head>
<body>
    <div class="appointments-container">
        <h1>New Appointments</h1>
        <table>
            <thead>
                <tr>
                    <th>Appointment No.</th>
                    <th>Time Slot</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newAppointments as $appointment): ?>
                    <tr>
                        <td><?php echo $appointment['AppointmentNo']; ?></td>
                        <td><?php echo $appointment['TimeSlot']; ?></td>
                        <td><?php echo $appointment['Date']; ?></td>
                        <td>
                        <a href="profile.php" class="view-button">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
