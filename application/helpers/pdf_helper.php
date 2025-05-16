<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

function generate_pdf_dompdf($html, $filename = 'document.pdf', $download = false) 
{
    // Load Composer's autoloader (if using Composer)
    // require_once APPPATH . '../vendor/autoload.php';

    // OR, if manually installed:
    require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

    // Configure DOMPDF
    $options = new Options();
    $options->set('isRemoteEnabled', true); // Allow external images/CSS
    $options->set('defaultFont', 'Helvetica'); // Default font

    $dompdf = new Dompdf($options);

    // Load HTML content
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait'); // 'landscape' for horizontal

    // Render the PDF
    $dompdf->render();

    // Output the PDF
    if ($download) {
        $dompdf->stream($filename, ['Attachment' => 1]);
    } else {
        return $dompdf->output(); // Returns PDF as string
    }
}