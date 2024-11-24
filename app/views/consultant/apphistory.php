<?php
// Sample array for demonstration purposes (replace with database query)
$appointments = [
    ['AppointmentNo' => 'A101', 'TimeSlot' => '10:00 AM - 11:00 AM', 'Date' => '2024-11-17', 'Status' => 'Accepted'],
    ['AppointmentNo' => 'A102', 'TimeSlot' => '12:00 PM - 1:00 PM', 'Date' => '2024-11-18', 'Status' => 'Rejected'],
    ['AppointmentNo' => 'A103', 'TimeSlot' => '2:00 PM - 3:00 PM', 'Date' => '2024-11-19', 'Status' => 'Accepted'],
    ['AppointmentNo' => 'A104', 'TimeSlot' => '3:00 PM - 4:00 PM', 'Date' => '2024-11-20', 'Status' => 'Rejected'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments List</title>
    <link rel="stylesheet" href="apphistory.css">
</head>
<body>
    <div class="appointments-container">
        <h1>Appointments History</h1>
        <table>
            <thead>
                <tr>
                    <th>Appointment No.</th>
                    <th>Time Slot</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo $appointment['AppointmentNo']; ?></td>
                        <td><?php echo $appointment['TimeSlot']; ?></td>
                        <td><?php echo $appointment['Date']; ?></td>
                        <td class="<?php echo strtolower($appointment['Status']); ?>">
                            <?php echo $appointment['Status']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
