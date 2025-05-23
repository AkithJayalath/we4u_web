<?php 
function uploadMultipleFiles($files, $location) {
    $uploadedFiles = [];
    $uploadErrors = [];

    // Loop through each file
    for ($i = 0; $i < count($files['name']); $i++) {
        // Generate a unique file name
        $fileName = time() . '_' . basename($files['name'][$i]);
        $tempFilePath = $files['tmp_name'][$i];

        // Use the existing `uploadImage` function to upload each file
        if (uploadImage($tempFilePath, $fileName, $location)) {
            $uploadedFiles[] = $fileName;  // Collect successful file names
        } else {
            $uploadErrors[] = "Failed to upload " . $files['name'][$i];
        }
    }

    // Return an array with the uploaded files and any errors
    return [
        'uploadedFiles' => $uploadedFiles,
        'errors' => $uploadErrors
    ];
}

?>