<?php
// Start session to handle form submission and feedback
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data here (you can store it in the database or send emails)
    echo "<script>alert('We received your payment details. We\'ll let you know about your appointment within today.');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <link rel="stylesheet" href="appointmentform.css">
    <script src="appointmentform.js" defer></script>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <h1>Book an Appointment</h1>
    </header>

    <!-- Appointment Form -->
    <div class="form-container">
        <form action="appointmentform.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="patient-name">Patient Name</label>
                <input type="text" id="patient-name" name="patient_name" placeholder="Enter your name" required>
            </div>

            <div class="input-group">
                <label for="consultant-name">Consultant Name</label>
                <input type="text" id="consultant-name" name="consultant_name" placeholder="Enter consultant's name" required>
            </div>

            <div class="input-group">
                <label for="appointment-date">Date</label>
                <input type="date" id="appointment-date" name="appointment_date" required>
            </div>

            <div class="input-group">
                <label for="from-time">Time From</label>
                <input type="time" id="from-time" name="from_time" required>
                <!-- <select name="from-period" id="from-period" required> -->
                    <!-- <option value="AM">AM</option>
                    <option value="PM">PM</option> -->
                <!-- </select> -->
            </div>

            <div class="input-group">
                <label for="to-time">Time To</label>
                <input type="time" id="to-time" name="to_time" required>
                <!-- <select name="to-period" id="to-period" required>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option> -->
                <!-- </select> -->
            </div>

            <!-- File Upload Section -->
            <div class="upload-section">
                <label for="paymentSlip" class="upload-label">Upload Payment Slip (JPG, PNG, PDF)</label>
                <input type="file" id="paymentSlip" name="paymentSlip" accept=".jpg, .jpeg, .png, .pdf" class="upload-input" required>
            </div>

            <!-- Error Message (Initially Hidden) -->
            <div id="error-message" style="color: red; display: none; font-size: 14px; margin-top: 10px;">
                <p>Please upload the <b>payment slip</b> before submitting the form.</p>
            </div>


            <div class="form-actions">
                <!-- Submit Button -->
            <div class="form-section">
                <button type="submit" class="submit-btn" onclick="showConfirmation(event)">Submit</button>
            </div>

            </div>
        </form>
    </div>
</body>
</html>
