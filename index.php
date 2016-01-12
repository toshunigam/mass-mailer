<!DOCTYPE html>
<html>
<head>
    <title>CKEditor Sample</title>
    <script src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript" language="javascript">
	function valid(){
		var reciever = document.forms["mailer"]["client-mail"].value;
		if(reciever==null || reciever==""){
			alert("Client mail feild is empty!");
			return false;
		}
		var sender = document.forms["mailer"]["sender-mail"].value;
		if(sender==null || sender==""){
			alert("Sender mail feild is empty!");
			return false;
		}
		var nompts = document.forms["mailer"]["nompts"].value;
		if(isNaN(nompts) || nompts==""){
			alert("No of mail per time slot feild must be a number.");
			document.forms["mailer"]["nompts"].focus();
			return false;
		}
		var header = document.forms["mailer"]["header"].value;
		if(header==null || header==""){
			alert("header feild is empty!");
			document.forms["mailer"]["header"].focus();
			return false;
		}
		var subject = document.forms["mailer"]["subject"].value;
		if(subject==null || subject==""){
			alert("subject feild is empty!");
			document.forms["mailer"]["subject"].focus();
			return false;
		}
	}
	</script>
</head>
<body>
    <form method="post" name="mailer" action="example.php" enctype="multipart/form-data">
		<table border="1">
			<tr>
				<td>Select client mail : <input type="file" name="client-mail" value=""/></td>
				<td>Select sender mail : <input type="file" name="sender-mail" value=""/></td>
			</tr>
			<tr>
				<td>No of mail/time slot : <input type="text" name="nompts" value="" /></td>
				<td>Time interval/slot : <select name="tips" size="1">
				<option value="60">1 Minute</option>
				<option value="180">3 Minute</option>
				<option value="300">5 Minute</option>
				<option value="480">8 Minute</option>
				<option value="720">12 Minute</option>
				<option value="900">15 Minute</option>
				<option value="1200">20 Minute</option>
				</select>
				</td>
			</tr>
			<tr>
				<td>Header : <textarea name="header"></textarea></td>
				<td>Subject : <textarea name="subject"></textarea></td>
			</tr>
		</table>
        <p>
            My Editor:<br>
            <textarea name="editor1">&lt;p&gt;Write your mail here...&lt;/p&gt;</textarea>
            <script>
                CKEDITOR.replace( 'editor1' );
            </script>
        </p>
        <p>
            <input type="submit" name="submit" onClick="return valid()">
        </p>
    </form>
</body>
</html>