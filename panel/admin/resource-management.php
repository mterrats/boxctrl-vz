<?php
session_start();
if(!file_exists('../../superusers/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}

if(isset($_POST['dedicatedramsubmit'])){
$ctid = $_POST['ctid'];
$dedicatedram = $_POST['dedicatedram'] * 1024  / 4;
$outputShell = shell_exec("sudo vzctl set $ctid --vmguarpages $dedicatedram --save");
}

if(isset($_POST['burstableramsubmit'])){
$ctid = $_POST['ctid'];
$burstableram = $_POST['burstableram'] * 1024  / 4;
$outputShell = shell_exec("sudo vzctl set $ctid --privvmpages $burstableram --save");
}

if(isset($_POST['diskspacesubmit'])){
$ctid = $_POST['ctid'];
$diskspace = $_POST['diskspace'];
$outputShell = shell_exec('sudo vzctl set '.$ctid.' --diskspace '.$diskspace.'G --save');
}

if(isset($_POST['cpulimitsubmit'])){
$ctid = $_POST['ctid'];
$cpulimit = $_POST['cpulimit'];
$outputShell = shell_exec("sudo vzctl set $ctid --cpus $cpulimit --save");
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
					<li><a href="vps-management.php" class="active">User/VPS Management</a></li>
					<li><a href="ip-management.php">IP Management</a></li>					
				</ul>			
		</div>
		
		<div class="clear"></div>
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
	
		<div class="toplinks">	
				<ul>		

					<li><a href="vps-management.php">User Management</a></li>
					<li><a href="#"><strong>Resource Management</strong></a></li>						
				</ul>	
		</div>	
		
				<strong><?php if(@$outputShell){echo "<pre>$outputShell</pre>";} ?></strong>	
				
				<div class="in minitext">
					<h2>Resource Management</h2>
					<p>Use the forms below to change different container resource limits. Just enter the container ID, new resource limit (numbers only), and hit enter.</p>
				</div>
		

				<div class="in minitext">
					<h3>Dedicated RAM Limit:</h3>
				</div>

				<div class="in forms">
				<table width="100%" border="0">
				<tr>
					<td style="background-color:#eaeaea;" width="33%"><b>Container ID Number:</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Dedicated RAM Amount (in MB):</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Update Container Configuration:</b></td>					
				</tr>
				<tr>
				<form method="post" action="">
					<td><input type="text" name="ctid" /></td>
					<td><input type="text" name="dedicatedram" /></td>
					<td><input type="submit" name="dedicatedramsubmit" value="Update Dedicated RAM" tabindex="5" /></td>			
				</form>
				</tr>
				</table>
				</div>			
		
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />
				
				<div class="in minitext">
					<h3>Burstable RAM Limit:</h3>
				</div>

				<div class="in forms">
				<table width="100%" border="0">
				<tr>
					<td style="background-color:#eaeaea;" width="33%"><b>Container ID Number:</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Burstable RAM Amount (in MB):</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Update Container Configuration:</b></td>					
				</tr>
				<tr>
				<form method="post" action="">
					<td><input type="text" name="ctid" /></td>
					<td><input type="text" name="burstableram" /></td>
					<td><input type="submit" name="burstableramsubmit" value="Update Burstable RAM" tabindex="5" /></td>			
				</form>
				</tr>
				</table>
				</div>			
		
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />

				<div class="in minitext">
					<h3>Disk Space Limit:</h3>
				</div>

				<div class="in forms">
				<table width="100%" border="0">
				<tr>
					<td style="background-color:#eaeaea;" width="33%"><b>Container ID Number:</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Disk Space Limit (in GB):</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Update Container Configuration:</b></td>					
				</tr>
				<tr>
				<form method="post" action="">
					<td><input type="text" name="ctid" /></td>
					<td><input type="text" name="diskspace" /></td>
					<td><input type="submit" name="diskspacesubmit" value="Update Disk Space Limit" tabindex="5" /></td>			
				</form>
				</tr>
				</table>
				</div>			
		
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />	

				<div class="in minitext">
					<h3>Number of Available CPUs:</h3>
				</div>

				<div class="in forms">
				<table width="100%" border="0">
				<tr>
					<td style="background-color:#eaeaea;" width="33%"><b>Container ID Number:</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Number of Available CPUs:</b></td>
					<td style="background-color:#eaeaea;" width="33%"><b>Update Container Configuration:</b></td>					
				</tr>
				<tr>
				<form method="post" action="">
					<td><input type="text" name="ctid" /></td>
					<td><input type="text" name="cpulimit" /></td>
					<td><input type="submit" name="cpulimitsubmit" value="Update Available CPU Limit" tabindex="5" /></td>			
				</form>
				</tr>
				</table>
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


		</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>