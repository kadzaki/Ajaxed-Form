<?php
	session_start();
	$result = true;
	if($_POST) include_once '../captcha/securimage.php';
    else	   include_once 'captcha/securimage.php';
	$captchaCode = ($_GET['captchaCode']) ?$_GET['captchaCode'] : $_POST['captchaCode'];
	$securimage = new Securimage();
	if ($securimage->check($captchaCode) == false) {
		$result = false; 
	}
	echo $result;
?>