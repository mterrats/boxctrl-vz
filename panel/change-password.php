<?php
session_start();
if(!file_exists('../users/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}
$error = false;
if(isset($_POST['change'])){
	if(strlen($_POST['n_password']) > 5){
		$old = sha1(md5($_POST['o_password']));
		$new = sha1(md5($_POST['n_password']));
		$c_new = sha1(md5($_POST['c_n_password']));
		$xml = new SimpleXMLElement('../users/' . $_SESSION['username'] . '.xml', 0, true);
		if($old == $xml->password){
			if($new == $c_new){
				$xml->password = $new;
				$xml->asXML('../users/' . $_SESSION['username'] . '.xml');
				header('Location: login.php');
				die;
			}
		}
	}
    $error = true;
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
					<li><a href="reinstall.php">Reinstall OS</a></li>					
				</ul>			
		</div>
		
		<div class="clear"></div>
	
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
		
				<div class="in minitext">
					<h2>Change Password</h2>
					<p>This page will change your password for BoxCtrl-VZ, not your VPS root password.</p>
				</div>

	<div class="in forms">			
    <form method="post" action="">
        <?php 
        if($error){
            echo '<p><u><h4>ERROR: A Password(s) has been typed wrong! Passwords must be at least six characters long. Try Again!</u></h4></p>';
        }
        ?>
        <p>Old password <br /><input type="password" name="o_password" class="box"	 /></p>
        <p>New password <br /> <input type="password" name="n_password" class="box" /></p>
        <p>Confirm new password <br /> <input type="password" name="c_n_password" class="box" /></p>
        <p><input type="submit" name="change" value="Change Password" tabindex="5" class="com_btn" /></p>
    </form>
	</div>
		
		</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>