<?php
require_once 'class.phpmailer.php';
// Test CVS
require_once 'Excel/reader.php';
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();  //object for reciever email list
$sender = new Spreadsheet_Excel_Reader(); //object for sender email list

// Set output Encoding.
$data->setOutputEncoding('CP1251');
$sender->setOutputEncoding('CP1251');
/***
* if you want you can change 'iconv' to mb_convert_encoding:
* $data->setUTFEncoder('mb');
*
**/

/***
* By default rows & cols indeces start with 1
* For change initial index use:
* $data->setRowColOffset(0);
*
**/

/***
*  Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/
$reciever = $_FILES['client-mail']['name'];
$mailsender = $_FILES['sender-mail']['name'];
$path = "";
//check if same file is alreadry present into the directory
if(file_exists($path.$reciever)) { 
 unlink($path.$reciever); //delete that file if already present
}
//check if same file is alreadry present into the directory
if(file_exists($path.$mailsender)) { 
 unlink($path.$mailsender); //delete that file if already present into the directory
}
//move those(.xsl files) file into directory
move_uploaded_file($_FILES['client-mail']['tmp_name'], $path.$_FILES['client-mail']['name']);
move_uploaded_file($_FILES['sender-mail']['tmp_name'], $path.$_FILES['sender-mail']['name']);

$data->read($reciever);
$sender->read($mailsender);
$subject = $_POST['subject'];
$message = $_POST['editor1'];

/*
 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
$data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
$data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
$data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
$data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/

error_reporting(E_ALL ^ E_NOTICE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "  http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Mass Mailer</title>
</head>

<body>
<!-- Progress bar holder -->
<div id="progress" style="margin:0 auto;width:500px;border:1px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="margin-left:424px;width"></div>
<table border="1">
<?php
ob_end_flush();
define('TOTAL', (int)$_POST['nompts']);
//number of mail per time slot
$total      = (int)$_POST['nompts'];
$start      = 1;
$p          = 2;
//total number of loop being looped
$outerlooop = ceil($data->sheets[0]['numRows']/$total);

//$total1 = $data->sheets[0]['numRows'];
for($i = 1; $i <= $outerlooop; $i++){// this one is outer loop
	for($j = $start; $j<=$total; $j++){ // this is inner loop
		$clientmail = $data->sheets[0]['cells'][$j+1][1];
		//check that $clientmail is not NULL or empty
		if(!empty($clientmail) || !is_null($clientmail)){
			if($j==1){
				$to = $data->sheets[0]['cells'][$j+1][1];
			}else{
				$to = $data->sheets[0]['cells'][$j+1][1];
			}
			//check for sender file row  is not empty or NULL
			if($p<= $sender->sheets[0]['numRows'] && !is_null($sender->sheets[0]['cells'][$p][1])){
				$from = $sender->sheets[0]['cells'][$p][1];
			}else{
				$p = 2;
				$from = $sender->sheets[0]['cells'][$p][1];
			}
			//echo "<p>".$to.'---------'.$from."</p><br/>";
			$mail = new PHPMailer;

			$mail->IsSMTP();                       // Set mailer to use SMTP
			$mail->Host       = 'yourhost.com';    // Specify main and backup server
			$mail->SMTPAuth   = true;              // Enable SMTP authentication
			$mail->Username   = 'your@email.com';  // SMTP username
			$mail->Password   = '*************';   // SMTP password
			$mail->SMTPSecure = 'tls';             //tls// Enable encryption, 'ssl' also accepted

			$mail->From       = $from;
			$mail->FromName   = ''; 	           //you can put your name here 
			$mail->AddAddress($to, '');            // Add a recipient $mail->AddAddress(toshuchandra@gmail.com, 'Toshu');

			$mail->WordWrap   = 50;                // Set word wrap to 50 characters
			$mail->IsHTML(true);                   // Set email format to HTML

			$mail->Subject    = $subject;
			$mail->Body       = 'If you are unable to view this mail Please <a href="#" >click here</a>';
			$mail->Body      .= $message;
			$mail->AltBody    = 'This is the body in plain text for non-HTML mail clients';

			if(!$mail->Send()) {
			   echo 'Message could not be sent.';
			   echo 'Mailer Error: ' . $mail->ErrorInfo;
			   exit;
			}

			//echo 'Message has been sent';

						
		}
	}
	$p++;// $p is increased by 1 every innerloop complete their loop and
	$start = $start+TOTAL; // $start is increased by TOTAL every innerloop complete their loop and
	$total = $total+TOTAL; // $total is increased by TOTAL every innerloop complete their loop
	// Calculate the percentation
	$percent = intval($i/$outerlooop * 100)."%";
	// Javascript for updating the progress bar and information
	echo '<script language="javascript">
	document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-image:url(progress-bar/pbar-ani.gif);\">&nbsp;</div>";
	document.getElementById("information").innerHTML="<p style=\"color:red;\">'.$i.' slot is procesing.</p>";
	</script>';		
	
	echo str_repeat(' ',24*64);
	// Send output to browser immediately
	flush();
	// Sleep one second so we can see the delay
	 sleep($_POST['tips']);
	// sleep(5);
} 
echo '<script language="javascript">document.getElementById("information").innerHTML="<p style=\"color:green;\">Process completed.</p>"</script>';
//print_r($data);
//print_r($data->formatRecords);
?>
</body>
</html>