$(function(){
	
	/*
	 * Form input validation ( using the jQuery validate plugin )
	 */
	$("#contactForm").validate({
		 submitHandler: function() {
			isCaptchaCodeValid(); 	
 		}
	});
	
	/*
	 * Check if the captcha code is valid
	 */
	function isCaptchaCodeValid(){
		var captchaCode = $("#captchaCode").val();
		var dataString = 'captchaCode='+captchaCode;
		$.ajax({
				type: "GET",
				url: "bin/checkCode.php",
				data: dataString,
				success: function(status){
					if(status) submitForm();
					else{
						alert("Incorrect code , try again");
					}
				}
			});	
	}
	
	/*
	 * Send the form using Ajax
	 */
	function submitForm(){
			$('#submit').html("<div id='loadingImage'></div>");
			$('#loadingImage').html("<img class='ajax-loader' style='visibility: visible;' alt='ajax loader' src='images/ajax-loader.gif' />");
			var name = $("input#name").val();
			var email = $("input#email").val();
			var subject = $("input#subject").val();
			var message = $("#message").val();
			var captchaCode = $("#captchaCode").val();
			var dataString = 'name=' + name + '&email=' + email + '&subject=' + subject + '&message=' + message + '&captchaCode=' + captchaCode;
			$.ajax({
				type: "GET",
				url: "bin/process.php",
				data: dataString,
				success: function(status){
					if(status){
					$('#wrap').html("<div id='statusMessage'></div>");
					$('#statusMessage').html("<h2>Thank you! We have received your message</h2>").append("<p>We will be in touch soon</p>").hide().fadeIn(1500, function(){
						$('#statusMessage').append("<img id='checkmark' src='images/check.png' />");
					});
					}else{
						$('#wrap').html("<div id='statusMessage'></div>");
						$('#statusMessage').html("<h2>Sorry, unexpected error. Please try again later</h2>");
					}
				}
			});
			return false;
	}
});