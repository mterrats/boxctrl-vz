<?php
session_start();
if(!file_exists('../../superusers/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}

if(isset($_POST['suspend'])){
$user_suspend = $_POST['usersuspend'].'.xml';
$xml = new SimpleXMLElement("../../users/$user_suspend", 0, true);
$xml->status = "suspended";
$ctid = $xml->ctid;
$xml->asXML("../../users/$user_suspend");
rename("../../users/$user_suspend", "../../users/SUSPENDED_$user_suspend");
shell_exec("sudo vzctl stop $ctid");
shell_exec("sudo vzctl set $ctid --disabled yes --save");
$outputShell = "The user and VPS has successfully been suspended.";
}

if(isset($_POST['unsuspend'])){
$user_unsuspend = $_POST['userunsuspend'].'.xml';
$unsuspend_username = substr($user_unsuspend, 10);
$xml = new SimpleXMLElement("../../users/$user_unsuspend", 0, true);
$xml->status = "active";
$ctid = $xml->ctid;
$xml->asXML("../../users/$user_unsuspend");
rename("../../users/$user_unsuspend", "../../users/$unsuspend_username");
shell_exec("sudo vzctl set $ctid --disabled no --save");
shell_exec("sudo vzctl start $ctid");
$outputShell = "The user and VPS has successfully been unsuspended.";
}

if(isset($_POST['terminate'])){
$user_terminate = $_POST['userterminate'].'.xml';
$xml = new SimpleXMLElement("../../users/$user_terminate", 0, true);
$ctid = $xml->ctid;
unlink("../../users/$user_terminate");
shell_exec("sudo vzctl stop $ctid");
shell_exec("sudo vzctl destroy $ctid");
$outputShell = "The user and VPS has successfully been terminated.";
}

if(isset($_POST['stopcontainer'])){
$ctid = $_POST['ctid'];
$outputShell = shell_exec("sudo vzctl stop $ctid");
}

if(isset($_POST['startcontainer'])){
$ctid = $_POST['ctid'];
$outputShell = shell_exec("sudo vzctl start $ctid");
}

if(isset($_POST['rebootcontainer'])){
$ctid = $_POST['ctid'];
$outputShell = shell_exec("sudo vzctl restart $ctid");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>BoxCtrl-VZ</title>
<link rel="stylesheet" type="text/css"  href="css/main.css" />
<script src="javascript/sorttable.js"></script>
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

					<li><a href="#"><strong>User Management</strong></a></li>
					<li><a href="resource-management.php">Resource Management</a></li>						
				</ul>	
		</div>	
		
				<strong><?php if(@$outputShell){echo "<pre>$outputShell</pre>";} ?></strong>	
				
				<div class="in minitext">
					<h2>User & VPS Management</h2>
				</div>

				<div class="in minitext">
					<h3>Suspend User/VPS:</h3>
					<p>Users who have already been suspended will have the prefix of "SUSPENDED_" before their names.</p>					
				</div>

				<div class="in forms">			
				<form method="post" action="">
				<?php
				$currentdir = '../../users/';
				$dir = opendir($currentdir);
				echo '<select name="usersuspend" class="box">';
				while($username = readdir($dir)){
					if ($username != "." && $username != "..") {
					echo '<option value="'.basename($username, '.xml').'">'.basename($username, '.xml').'</option>';
					}
				}
				echo '</select>'; 
				closedir($dir); 				
				?>					
					<p><input type="submit" name="suspend" value="Suspend User/VPS" tabindex="5" class="com_btn" /></p>
				</form>
				</div>		

				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />
		
				<div class="in minitext">
					<h3>Unsuspend User/VPS:</h3>
					<p>Only unsuspend users with the "SUSPENDED_" prefix.</p>					
				</div>

				<div class="in forms">			
				<form method="post" action="">		
				<?php
				$currentdir = '../../users/';
				$dir = opendir($currentdir);
				echo '<select name="userunsuspend" class="box">';
				while($username = readdir($dir)){
					if ($username != "." && $username != "..") {
					echo '<option value="'.basename($username, '.xml').'">'.basename($username, '.xml').'</option>';
					}
				}
				echo '</select>'; 
				closedir($dir); 				
				?>	
					<p><input type="submit" name="unsuspend" value="Unsuspend User/VPS" tabindex="5" class="com_btn" /></p>
				</form>
				</div>
				
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />
		
				<div class="in minitext">
					<h3>Terminate User/VPS:</h3>
					<p>This will delete the user's VPS and user file from the node and all data will be lost. This can not be undone.</p>
				</div>

				<div class="in forms">			
				<form method="post" action="">		
				<?php
				$currentdir = '../../users/';
				$dir = opendir($currentdir);
				echo '<select name="userterminate" class="box">';
				while($username = readdir($dir)){
					if ($username != "." && $username != "..") {
					echo '<option value="'.basename($username, '.xml').'">'.basename($username, '.xml').'</option>';
					}
				}
				echo '</select>'; 
				closedir($dir); 				
				?>
<p><input type="submit" name="terminate" value="Terminate User/VPS" tabindex="5" class="com_btn"  onclick="return confirm('Are you sure you want to terminate this user? This will delete their VPS and user file from the node and all data will be lost. This can not be undone.'); " /></p>
				</form>
				</div>
				
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />
				
				<div class="in minitext">
					<h3>Stop Container:</h3>
				</div>				

				<div class="in forms">
				<table width="600px" border="0">
				<tr>
					<td style="background-color:#eaeaea;" width="50%"><b>Container ID Number:</b></td>
					<td style="background-color:#eaeaea;" width="50%"><b>Send Command:</b></td>					
				</tr>
				<tr>
				<form method="post" action="">
					<td><input type="text" name="ctid" /></td>
					<td><input type="submit" name="stopcontainer" value="Stop Container" tabindex="5" /></td>			
				</form>
				</tr>
				</table>
				</div>				
				
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />
				
				<div class="in minitext">
					<h3>Start Container:</h3>
				</div>				

				<div class="in forms">
				<table width="600px" border="0">
				<tr>
					<td style="background-color:#eaeaea;" width="50%"><b>Container ID Number:</b></td>
					<td style="background-color:#eaeaea;" width="50%"><b>Send Command:</b></td>					
				</tr>
				<tr>
				<form method="post" action="">
					<td><input type="text" name="ctid" /></td>
					<td><input type="submit" name="startcontainer" value="Start Container" tabindex="5" /></td>			
				</form>
				</tr>
				</table>
				</div>	
				
				<hr style="color:#eaeaea;background-color:#eaeaea;height:1px;border:none;" />
				
				<div class="in minitext">
					<h3>Reboot Container:</h3>
				</div>				

				<div class="in forms">
				<table width="600px" border="0">
				<tr>
					<td style="background-color:#eaeaea;" width="50%"><b>Container ID Number:</b></td>
					<td style="background-color:#eaeaea;" width="50%"><b>Send Command:</b></td>					
				</tr>
				<tr>
				<form method="post" action="">
					<td><input type="text" name="ctid" /></td>
					<td><input type="submit" name="rebootcontainer" value="Reboot Container" tabindex="5" /></td>			
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
					<td style="background-color:#eaeaea;" ><b>Username</b></td>
					<td style="background-color:#eaeaea;" ><b>Assigned CTID</b></td>
					<td style="background-color:#eaeaea;" ><b>Account Status</b></td>					
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