<?php
session_start();
if(!file_exists('../users/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}
$ctid = $_SESSION['ctid'];
$outputShell = "";

if(isset($_POST['changehostname'])){
	$newhostnamesecure = preg_replace('/[^A-Za-z0-9.]/', '', $_POST['n_hostname']);
		$outputShell = shell_exec("sudo vzctl set $ctid --hostname $newhostnamesecure --save");		
}

if(isset($_POST['changerootpassword'])){
    $newpasswordsecure = preg_replace('/\s+/', '', $_POST['n_password']);
        $outputShell = shell_exec("sudo vzctl set $ctid --userpasswd root:$newpasswordsecure --save");
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
					<li><a href="vps-settings.php" class="active">VPS Settings</a></li>
					<li><a href="reinstall.php">Reinstall OS</a></li>																				
				</ul>			
		</div>
		
		<div class="clear"></div>
	
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
	
				<strong><?php if($outputShell){echo "<pre>$outputShell</pre>";} ?></strong>	
					
				<div class="in minitext">
					<h3>Change Hostname:</h3>
				</div>

				<div class="in forms">			
				<form method="post" action="">		
					<p><input type="text" name="n_hostname" class="box" /></p>
					<p><input type="submit" name="changehostname" value="Change Hostname" tabindex="5" class="com_btn" /></p>
				</form>
				</div>			
		
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />
		
				<div class="in minitext">
					<h3>Change Root Password:</h3>
				</div>

				<div class="in forms">			
				<form method="post" action="">		
					<p><input type="text" name="n_password" class="box" /></p>
					<p><input type="submit" name="changerootpassword" value="Change Root Password" tabindex="5" class="com_btn" /></p>
				</form>
				</div>
		
				</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>