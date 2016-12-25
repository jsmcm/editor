<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
     <meta charset="utf-8">
    <title>Editor Login</title>
	
    	<link rel="stylesheet" type="text/css" href="stylesheets/style.css">
	
    	<link href="http://fonts.googleapis.com/css?family=Questrial" rel="stylesheet" type="text/css"/>
    	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="stylesheets/iexplorer.css">
  </head>
  
  <body>
	<section id="wrapper">
		<section id="form">

			<?php
			if( (isset($_GET["Notes"])) && ($_GET["Notes"] != "") )
			{
			?> 
			<span class="notes"><?php print filter_var($_GET["Notes"], FILTER_SANITIZE_STRING); ?></span>
			<?php
			}
			?>
				<br/>				
		        <h1>Log in to your account</h1>
				<form name="login-form" id="smart-login" method="post" action="DoLogin.php">
					<fieldset id="smart-login-fields">
						<input id="username" name="EmailAddress" type="text" placeholder="Email Address" required>
						<br/>
						<input id="password" type="password" placeholder="Password" name="Password" required>
					</fieldset>
					<!--span class="password-reset"><a href="#">Forgot Your Password?</a></span><br/><br/><br/-->
					<fieldset id="smart-login-actions">
						<input type="reset" id="reset" value="Reset">
						<input type="submit" id="login" value="Log in">
					</fieldset>
					<br/>
	  			 </form>
		</section>

	</section>
  </body>
</html>
