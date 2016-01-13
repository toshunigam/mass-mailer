<?php
if(isset($_POST) && !empty($_POST)){
require 'class.phpmailer.php';
$mail = new PHPMailer;
/***********************************************************/
for($i=0; $i<count($_FILES['attachment']['error']); $i++){
	$file_name = $_FILES['attachment']['name'][$i];
	$temp_name = $_FILES['attachment']['tmp_name'][$i];
	$file_type = $_FILES['attachment']['type'][$i];
	$mail->AddAttachment($temp_name, $file_name);         // Add attachments
}
/***********************************************************/
$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = '109.203.122.32';  // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'anurag@bookmyproperties.co.in';                            // SMTP username
$mail->Password = 'toshunigam123';                           // SMTP password
$mail->SMTPSecure = 'tls';                            //tls// Enable encryption, 'ssl' also accepted

$mail->From = 'toshunigam_1987@rediffmail.com';
$mail->FromName = 'Toshu Nigam';
$mail->AddAddress('toshuchandra@gmail.com', 'Toshu Nigam');  // Add a recipient
//$mail->AddAddress('toshunigam@ymail.com');               // Name is optional
$mail->AddReplyTo('toshunigam@ymail.com', 'Information');
$mail->AddCC('toshunigam@hotmail.com');
//$mail->AddBCC('');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->AddAttachment($temp_name, $file_name);         // Add attachments
//$mail->AddAttachment($file_name);    // Optional name
$mail->IsHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body = 'This attachment is done through <em>PHPMailer</em>, this is a great component for sending any type of <strong>e-mails</strong>.';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>attachment of mail</title>
</head>
<form method="post" name="" action="" enctype="multipart/form-data"> 
  
<label for='email'>Email: </label>
<input type="text" name="email" >
 
<label for='message'>Message:</label>
<textarea name="message"></textarea>
 <br/><br />
<label for='uploaded_file'>File Upload:</label>
<input type="file" name="attachment[]" multiple />
<input type="submit" value="Submit" name='submit'>
</form>
<body>
</body>
</html>