<?php
session_start();
if(!file_exists('../../superusers/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}

$message = "";
if(isset($_POST['add_range'])){

	$input = $_POST['ip_range'];

	function ipRange($input) {
		$input = explode("/", $input);
			$numerator = substr(strrchr($input[0], "."), 1,  3);
			$denominator = $input[1];
				$num = strlen($numerator);
			$range = substr($input[0], 0, -$num);
			
		while ($numerator <= $denominator) {		
			$xml = new SimpleXMLElement('<ipaddress></ipaddress>');
			$xml->addChild('ownership', '');
			$xml->asXML("../../ipaddresses/$range$numerator.xml");
			$numerator++;
		}
	}
	// Call function
	ipRange($input);
	$message = "IP Range Added.";
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>BoxCtrl-VZ</title>
<link rel="stylesheet" type="text/css"  href="css/main.css" />
</head>

<body>

	<div class="wrapper"><!-- Wrapper -->
	
		<h1 class="logo"><img src="images/logo.png" /></h1>
		<p class="txt_right">Logged in as <strong><?php echo $_SESSION['username']; ?></strong>  <span class="v_line"> | </span> <a href="change-password.php">Change Password</a> <span class="v_line"> | </span> <a href="logout.php"> Logout</a></p>
	
	<!-- Navigation -->
	
		<div class="navigation">
				<ul>
					<li><a href="index.php">Overview</a></li>
					<li><a href="create-new.php">Create User/VPS</a></li>
					<li><a href="vps-management.php">User/VPS Management</a></li>
					<li><a href="ip-management.php" class="active">IP Management</a></li>					
				</ul>			
		</div>
		
		<div class="clear"></div>
	
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
	
		<div class="toplinks">	
				<ul>		

					<li><a href="ip-management.php">IP Management</a></li>
					<li><a href="#"><strong>Add IP Range</strong></a></li>						
				</ul>	
		</div>		
		
				<div class="in minitext">
					<h2>Add IP Range</h2>
					<p>The IP range must be separated with a slash. Example: to add IPs 192.168.0.100 through 192.168.0.120, you would enter "192.168.0.100/120" without the quotes.</p>
				</div>

	<div class="in forms">
	<strong><?php if($message){echo "<pre>$message</pre>";} ?></strong>		
    <form method="post" action="">
		<p><b>Add IP Range:</b><br /> <input type="text" name="ip_range" class="box" /></p>
        <p><input type="submit" name="add_range" value="Add IP Range" tabindex="5" class="com_btn" /></p>
    </form>
	</div>
		
		</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>