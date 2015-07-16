<?php
session_start();
if(!file_exists('../../superusers/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}

$username = $_SESSION['username'];

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
					<li><a href="create-new.php">Create User/VPS</a></li>
					<li><a href="vps-management.php">User/VPS Management</a></li>
					<li><a href="ip-management.php">IP Management</a></li>																								
				</ul>			
		</div>
		
		<div class="clear"></div>
	
		<div class="content"><!-- content -->
		
	<!-- status -->

		<div class="in">
			<h3>NODE OVERVIEW <span style="float:right; color:#999;"><?php echo shell_exec("hostname"); ?></span></h3>
			
			<?php
			$outputShell = shell_exec("../../scripts/node_status.sh");
			echo "<pre>$outputShell</pre>";
			?>
		</div>
		
		<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />					

		<div class="in minitext">
			<h3>User Information</h3>
			<p>This chart is a breakdown of every user in the system, their assigned CTID number and their account status.</p>
		</div>
		<div class="in forms">
		<table width="100%" border="0" class="sortable">
		<tr>
			<td style="background-color:#eaeaea;" width="33%"><b>Username</b></td>
			<td style="background-color:#eaeaea;" width="33%"><b>Assigned CTID</b></td>
			<td style="background-color:#eaeaea;" width="33%"><b>Account Status</b></td>					
		</tr>
		<?php
		$currentdir = '../../users/';
		$dir = opendir($currentdir);
		while($username = readdir($dir)){
			if ($username != "." && $username != "..") {
				$xml = new SimpleXMLElement("../../users/$username", 0, true);
				echo "<tr>";
				echo "<td>";
				echo basename($username, '.xml');
				echo "</td>";
				echo "<td>";
				echo $xml->ctid;
				echo "</td>";
				echo "<td>";
				echo $xml->status;
				echo "</td>";						
				echo "</tr>";
			}
		}
		closedir($dir); 				
		?>
		</table>				
		</div>
		
		<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />

		<div class="in minitext">
			<h3>Reinstall Log</h3>		
			<?php
			$outputShell = shell_exec("cat ../../scripts/reinstall.log");
			echo "<pre>$outputShell</pre>";
			?>
		</div>		
		
	
		</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>