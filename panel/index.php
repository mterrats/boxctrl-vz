<?php
session_start();
if(!file_exists('../users/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}

$message = '';
$username = $_SESSION['username'];
$ctid = $_SESSION['ctid'];
$outputShell = "";

// Control Switch
if(isset($_GET['action'])){
	switch($_GET['action']) {
		// Container Control
		case "start_container":
			$outputShell = shell_exec("sudo vzctl start $ctid");
		break;
		case "stop_container":
			$outputShell = shell_exec("sudo vzctl stop $ctid");
		break;
		case "restart_container":
			$outputShell = shell_exec("sudo vzctl restart $ctid");
		break;	
		// Default Break
		default:
		break;
	}
}
$ctid_status = shell_exec("sudo vzctl status $ctid |cut -d\  -f5");
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
		<p class="txt_right">Logged in as <strong><?php echo $username; ?></strong>  <span class="v_line"> | </span> <a href="change-password.php">Change Password</a> <span class="v_line"> | </span> <a href="logout.php"> Logout</a></p>
	
	<!-- Navigation -->
	
		<div class="navigation">
				<ul>
					<li><a href="index.php" class="active">Overview</a></li>				
					<li><a href="vps-settings.php">VPS Settings</a></li>
					<li><a href="reinstall.php">Reinstall OS</a></li>																				
				</ul>			
		</div>
		
		<div class="clear"></div>
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
		
				<div class="in minitext">
					<h2>VPS Monitor and Control</h2>
				</div>
					
	<!--Monitors -->
	
		<div class="in">
		
		<strong><?php if($outputShell){echo "<pre>$outputShell</pre>";} ?></strong><br />

			<table width="100%" border="0" cellspacing="0" cellpadding="10" class="table_main" >
			  <tr style="background-color:#d9d8d8; font-size:14px;">
				<td width="63%"><strong>SERVER MONITOR</strong></td>
				<td width="14%"><strong>STATUS</strong></td>			
				<td width="23%"><strong>SERVER CONTROLS</strong></td>
			  </tr>
			  <tr class="gray">
			  <!-- Container Control -->
				<td><strong>VPS ID:</strong> <?php echo $ctid; ?></td>
				<td>			
				<strong>&bull; <font color=#f7941d><?php echo $ctid_status; ?></font> &bull;</strong>			
				</td>
				<td>
				<a href="index.php?action=start_container">START </a><span class="v_line">| <a href="index.php?action=stop_container">STOP </a><span class="v_line">| </span> <a href="index.php?action=restart_container">REBOOT </a></td>
			  </tr>
			  <tr>
			</table>
		</div>

		<div class="in">
<strong>Hostname:</strong> <pre><?php echo shell_exec("sudo vzctl exec $ctid hostname"); ?></pre><br />
<strong>Uptime and Load:</strong><pre><?php echo shell_exec("sudo vzctl exec $ctid uptime"); ?></pre><br />
<strong>Kernel Version:</strong> <pre><?php echo shell_exec("sudo vzctl exec $ctid uname -srm"); ?></pre><br />
<strong>Memory Usage:</strong> <pre><?php echo shell_exec("sudo vzctl exec $ctid free -m"); ?></pre><br />
<strong>Disk Usage:</strong> <pre><?php echo shell_exec("sudo vzctl exec $ctid df -h"); ?></pre></br >
<strong>IP Addresses:</strong> <pre><?php echo shell_exec("sudo vzlist $ctid -H -o ip"); ?></pre><br />
		</div>

	
		</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>