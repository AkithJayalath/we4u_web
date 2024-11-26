<?php
function calculateBMI($weight, $height) {
    if ($height > 0) {
        // Convert height from cm to meters
        $heightInMeters = $height / 100;
        // Calculate BMI
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        // Round BMI to one decimal place
        return round($bmi, 1);
    }
    return null; // Return null if height is invalid
}


?>