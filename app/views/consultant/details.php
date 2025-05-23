<?php
// Example data for the Appointment Details page (replace with dynamic data)
$appointmentDetails = [
    'AppointmentNo' => 'A201',
    'PatientName' => 'John Doe',
    'ConsultantName' => 'Dr. Sarah Connor',
    'Date' => '2024-11-26',
    'TimeSlot' => '10:00 AM - 11:00 AM',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="details.css">
</head>
<body>
    <div class="details-container">
        <h1>Appointment Details</h1>
        <table class="details-table">
            <tr>
                <th>Appointment No.</th>
                <td><?php echo $appointmentDetails['AppointmentNo']; ?></td>
            </tr>
            <tr>
                <th>Patient Name</th>
                <td><?php echo $appointmentDetails['PatientName']; ?></td>
            </tr>
            <tr>
                <th>Consultant Name</th>
                <td><?php echo $appointmentDetails['ConsultantName']; ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo $appointmentDetails['Date']; ?></td>
            </tr>
            <tr>
                <th>Time Slot</th>
                <td><?php echo $appointmentDetails['TimeSlot']; ?></td>
            </tr>
        </table>

    <div class="details-buttons">
        <button class="details-button">Check Availability</button>
        <button class="details-button">Accept</button>
        <button class="details-button reject">Reject</button>
        <button class="details-button">Chat</button>
    </div>

    </div>
</body>
</html>
