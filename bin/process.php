<?php 
session_start();
require("class.phpmailer.php");

if($_POST) include_once '../captcha/securimage.php';
else	   include_once 'captcha/securimage.php';

$errors = null ;    
//Retrieve form data.   
//GET - user submitted data using AJAX  
//POST - in case user does not support javascript, we'll use POST instead  

if ($_POST){
	$_GET['name'] = null;
	$_GET['email'] = null;
	$_GET['subject'] = null;
	$_GET['message'] = null;
	$_GET['captchaCode'] = null;
}

$name = ($_GET['name']) ? $_GET['name'] : $_POST['name'];  
$email = ($_GET['email']) ?$_GET['email'] : $_POST['email'];  
$subject = ($_GET['subject']) ?$_GET['subject'] : $_POST['subject'];  
$message = ($_GET['message']) ?$_GET['message'] : $_POST['message']; 
$captchaCode = ($_GET['captchaCode']) ?$_GET['captchaCode'] : $_POST['captchaCode'];
   
//Simple server side validation for POST data  
if (!$name) $errors[count($errors)] = 'Please enter your name.';  
if (!$email) $errors[count($errors)] = 'Please enter your email.';   
if (!$message) $errors[count($errors)] = 'Please enter your message.';  
if (!$captchaCode) $errors[count($errors)] = 'Please enter the verification code.'; 

	

//if the errors array is empty, send the mail  
if (!$errors) {   
	 $body = "From : ".$email."<br />".$message;
     //send the mail  
     $result = sendEmail($name,$email,$subject,$body);  
       
     //if POST was used, display the message straight away  
     if ($_POST) {  
         if ($result) echo 'Thank you! We have received your message.';  
         else echo 'Sorry, unexpected error. Please try again later';  
           
     //else if GET was used, return the boolean value so that   
     //ajax script can react accordingly  
     //1 means success, 0 means failed  
     } else {    	 
		 $securimage = new Securimage();
		if ($securimage->check($captchaCode) == false) {
  		// the code was incorrect
		$errors[count($errors)] = 'The code you entered was incorrect.  Go back and try again.'; 
		$result = false;
	}   
	echo $result;
     }  
   
 //if the errors array has values  
 } else {  
     //display the errors message  
     for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';  
     echo '<a href="../contact.html">Back</a>';  
     exit;  
}  

function sendEmail($name,$email,$subject,$body){
	$mail = new PHPMailer(); 
	
	/******* SMTP Configuration *******/
	/*
	 * This is the default configuration to send Emails using Gmail SMTP server , you can use it just like it is 
	 * you only need to change the Username and the Password and put your own , or you can use the configuration
	 * of your hosting provider 
	 */
	$mail->Mailer = "smtp";
	$mail->Host = "ssl://smtp.gmail.com"; 	// SMTP Host
	$mail->Port = 465;					    // SMTP Port
	$mail->SMTPAuth = true;                 // turn on SMTP authentication
	$mail->Username = "ensalaur@gmail.com"; // SMTP username (put your own gmail or SMTP server Username)
	$mail->Password = "ensalaur2009";       // SMTP password (put your own gmail or SMTP server Password)
	/****** END SMTP Configuration *****/
	
	$mail->AddAddress("yourEmail@gmail.com"); // Your Email address ( john@gmail.com for example )
	
	$mail->From     = $email;
	$mail->FromName = $name; 
	$mail->Subject  = $subject;
	$mail->Body     = $body;
	$mail->WordWrap = 50; 
	$mail->IsHTML(true);
	if(!$mail->Send()) {
  	echo 'Message was not sent.';
  	echo 'Mailer error: ' . $mail->ErrorInfo;
	return false;
	} else { return true; }
}
?>