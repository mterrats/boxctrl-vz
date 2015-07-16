<?php
session_start();
if(!file_exists('../users/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}
$ctid = $_SESSION['ctid'];
$datetime = date("D, d M Y H:i:s");
$outputShell = "";
$newpass = sha1(date("mdys"));

if(isset($_POST['reinstall'])){
	$operating_system = $_POST['reinstall'];
	shell_exec("echo '$ctid reinstall on $datetime' >> ../scripts/reinstall.log");
	shell_exec("../scripts/reinstall_os.sh $ctid $operating_system $newpass");
	$outputShell = "Complete. Your new root password is $newpass";
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
					<li><a href="vps-settings.php">VPS Settings</a></li>
					<li><a href="reinstall.php" class="active">Reinstall OS</a></li>															
				</ul>			
		</div>
		
		<div class="clear"></div>
	
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
	
				<strong><?php if($outputShell){echo "<pre>$outputShell</pre>";} ?></strong>	
					
				<div class="in minitext">
					<h2>Reinstall Operating System</h2>
					<p>Notice: <em>Reinstalling your OS will totally delete all of your existing data.</em></p>
				</div>

				<div class="in forms">			

				<?php
				echo   "<form method='post' action=''>";
				$currentdir = '/vz/template/cache';
				$dir = opendir($currentdir);	 
				echo '<select name="reinstall" class="box">'; 
				while($username = readdir($dir)){ 
					if ($username != "." && $username != "..") {
					echo '<option value="'.basename($username, '.tar.gz').'">'.basename($username, '.tar.gz').'</option>'; 
					}
				} 
				echo '</select>'; 
				closedir($dir); 				
				echo   "<br /><br /><input type='submit' name='change' value='Reinstall OS' class='com_btn' onclick=\"return confirm('Are you sure you want to reinstall your OS?');\" />";
				echo   "</form>";				
				?>
				
				<p><em><strong>IMPORTANT:</strong> After clicking on the reinstall button, do not leave this page or hit refresh until it has completed. The process can take up to five minutes and a new root password will be printed on this page when the process is complete.</em></p>

				</div>
		
				</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>