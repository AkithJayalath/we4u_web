<?php
function loadCSS($stylesheets) {
    $cssString = '';
    foreach ($stylesheets as $stylesheet) {
        $cssPath = URLROOT . '/css/' . $stylesheet . '.css';
        $cssString .= '<link rel="stylesheet" href="' . $cssPath . '">';
    }
    return $cssString;
}
?>
