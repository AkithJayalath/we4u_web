<?php
/**
 * PDF Helper Functions
 * Contains functions for generating PDFs in the We4U application using DomPDF
 */

// Import the Dompdf namespace at the top of the file
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Generate a PDF document from HTML content
 * 
 * @param string $title The title of the PDF
 * @param string $content The HTML content
 * @param string $filename The filename for the PDF
 * @param string $output The output method ('download' for download, 'inline' for browser display)
 * @return void
 */
function generate_pdf($title = 'We4U Care Services', $content = '', $filename = 'we4u_document.pdf', $output = 'download') {
    // Include the DomPDF library

    // Set options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // Allow loading images from remote URLs
    $options->set('defaultFont', 'Arial');

    // Initialize dompdf
    $dompdf = new Dompdf($options);

    // If content is empty, use a default message
    if (empty($content)) {
        $content = '<p>This is a simple PDF document generated using DomPDF library. You can customize this document with your own content as needed.</p>';
    }

    // Load HTML content directly
    $dompdf->loadHtml($content);

    // Set paper size (A4)
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF
    if ($output == 'download') {
        $dompdf->stream($filename, array('Attachment' => true));
    } else {
        $dompdf->stream($filename, array('Attachment' => false));
    }
    exit;
}
