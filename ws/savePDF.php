<?php 
//exec("/home/getmezej/public_html/lib/wkhtmltox/bin/wkhtmltopdf http://www.google.com pdf1.pdf");
require_once '../backend/config.php';
require_once '../backend/functions.php';
require_once '../backend/db_connection.php';
	
require_once('../lib/tcpdf/tcpdf.php');

if( strlen( json_encode($_POST) ) < 3 )
	echo "empty post data";
else
{
	$key  = $_POST['skey'];
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

	// add a page
	$pdf->AddPage();
	
	// create some HTML content
	$html = file_get_contents('http://www.getmecv.com/ws/pdf.php?key=' . $key);
	
	//echo $html;

	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');
	
	// reset pointer to the last page
	$pdf->lastPage();

	// ---------------------------------------------------------
	ob_clean();
	
	$file = $key  . '.pdf';
	//Close and output PDF document
	$pdf->Output('../cv/pdf/' . $file, 'F');
	
	echo 'http://www.getmecv.com/cv/pdf/' . $file;
}

		
?>