<?php
require 'config.php';
$error = false;
$ip = $_SERVER['REMOTE_ADDR'];
if (file_exists('../../blocked/' . $ip . '.xml')){
	$ipblock = new SimpleXMLElement('../../blocked/' . $ip . '.xml', 0, true);
	$attempts = $ipblock->attempts;
		if ($attempts == $failed_attempts){
			echo 'Your IP is banned for too many failed login attempts.';
			die;
		}			
}	
if(isset($_POST['login'])){
    $username = preg_replace('/[^A-Za-z]/', '', $_POST['username']);
    $password = sha1(md5($_POST['password']));
    if(file_exists('../../superusers/' . $username . '.xml')){
        $xml = new SimpleXMLElement('../../superusers/' . $username . '.xml', 0, true);
        if($password == $xml->password){
			if ($xml->status == "suspended"){
				die('This account has been suspended.');
			}
			session_start();
			$_SESSION['username'] = $username;
			header('Location: index.php');
			die;
		}
		elseif (file_exists('../../blocked/' . $ip . '.xml')){
			$ipblock = new SimpleXMLElement('../../blocked/' . $ip . '.xml', 0, true);
			$attempts = $ipblock->attempts;
			$attempts = $attempts + 1;
			$ipblock->attempts = $attempts;
			$ipblock->asXML('../../blocked/' . $ip.'.xml');					
		}
		else {
			$ipblock = new SimpleXMLElement('<ip></ip>');
			$ipblock->addChild('attempts', '1');
			$ipblock->asXML('../../blocked/' . $ip . '.xml');
		}
    $error = true;
	}
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
	<br />
	<div class="wrapper" style="margin:0 auto; width:660px;"><!-- Wrapper -->

		<div class="content"><!-- content -->
		
	<!-- Subnav -->
	<div class="in minitext">
	<h1><img src="images/logo.png" /></h1>
	<span style="float:right; color:#999;">Administrator Login</span>
	</div>
	
	<div class="in forms">	
	<form method="post" action="">
			<p>Username <input type="text" name="username" class="box" /></p>
			<p>Password <input type="password" name="password" class="box" /></p>
			<?php
			if($error){
				echo '<p>Invalid username and/or password</p>';
			}
			?>
			<p><input type="submit" value="Login" name="login" tabindex="5" class="com_btn" /></p>
	</form>
	</div>
		
		</div><!-- content -->
		
		<p class="footer">
		<span style="float:left; color:#999;">IP Logged: <?php echo $ip; ?></span>
		BoxCtrl-VZ - Version 1.01
		</p>
	</div><!-- Wrapper -->
</body>
</html>