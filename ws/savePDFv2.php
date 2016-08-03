<?php 
//./wkhtmltopdf http://www.getmecv.com/cv/cv.php?id=14# ../cv/pdf/47f547ca-4a9b-11e6-a92c-0cc47a6b12fe.pdf
if( strlen( json_encode($_POST) ) < 3 )
	echo "empty post data";
else
{
	$key  = $_POST['skey'];
	// You can pass a filename, a HTML string, an URL or an options array to the constructor
	//$pdf = new Pdf('http://www.getmecv.com/ws/pdf.php?key=' . $key);
	
	$file = $key  . '.pdf';
	 
	if(file_exists(' ../cv/pdf/' . $file))
		unlink(' ../cv/pdf/' . $file);
	
	$cmd = 'cd ../lib && ./wkhtmltopdf --user-style-sheet css/print.css ' . 'http://www.getmecv.com/ws/pdf2.php?key=' . $key . ' ../cv/pdf/' . $file;
	$out = shell_exec($cmd); 
	
	//echo($out); 
	echo 'http://www.getmecv.com/cv/pdf/' . $file;
}	
?>