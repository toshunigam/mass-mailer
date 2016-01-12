<?php
// Test CVS

require_once 'Excel/reader.php';


// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();
$sender = new Spreadsheet_Excel_Reader(); //object for sennder email list

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
//$file = 'book1.xls';
$data->read($reciever);
//$file1 = 'sender.xls';
$sender->read($mailsender);

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Mass Mailer</title>
</head>

<body>
<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>
<?php
	ob_end_flush();
	define('TOTAL', (int)$_POST['nompts']);

	$total      = (int)$_POST['nompts'];
	$start      = 1;
	$p          = 2;
	$outerlooop = $data->sheets[0]['numRows']/$total;
	$total1     = $data->sheets[0]['numRows'];
	
	for($i = 1; $i <= $outerlooop; $i++)
	{
		for($j = $start; $j<=$total; $j++){
			if(!empty($data->sheets[0]['cells'][$j][1]))
			{
				$to = $data->sheets[0]['cells'][$j][1];
				if($p <= $sender->sheets[0]['numRows']){
					$from = $sender->sheets[0]['cells'][$p][1];
				}else{
					$p = 2;
					$from = $sender->sheets[0]['cells'][$p][1];
				}
				echo "<p>".$to.'---------'.$from."</p><br/>";
				//mail($to, $from, $message, $header);
			}
		}//end loop 2nd
		$p++;
		$start   = $start+TOTAL;
		$total   = $total+TOTAL;
		// Calculate the percentation
		$percent = intval($i/$outerlooop * 100)."%";
		
		// Javascript for updating the progress bar and information
		echo '<script language="javascript">
		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-image:url(progress-bar/pbar-ani.gif);\">&nbsp;</div>";
		document.getElementById("information").innerHTML="<p style=\"color:red;\">'.$i.' slot is procesing.</p>";
		</script>';		
		//echo "<p>".$data->sheets[0]['cells'][$i][2]."</p><br/>";
		echo str_repeat(' ',24*64);
		// Send output to browser immediately
		flush();
		// Sleep one second so we can see the delay
		sleep(3);
	}//end loop 1st
	echo '<script language="javascript">document.getElementById("information").innerHTML="<p style=\"color:green;\">Process completed.</p>"</script>';
	//print_r($data);
	//print_r($data->formatRecords);
?>
</body>
</html>
