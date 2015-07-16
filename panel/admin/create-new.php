<?php
session_start();
if(!file_exists('../../superusers/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}

$errors = array();
if(isset($_POST['create'])){
    $boxctrlusername = preg_replace('/[^A-Za-z0-9]/', '', $_POST['boxctrlusername']);
    $password = $_POST['password'];
	$ctid = $_POST['ctid'];
	$hostname = $_POST['hostname'];
	$rootpassword = $_POST['rootpassword'];
	$ram = $_POST['ram'] * 1024  / 4;
	$burstram = $_POST['burstram'] * 1024 / 4;
	$diskspace = $_POST['diskspace'];
	$nameserver1 = $_POST['nameserver1'];
	$nameserver2 = $_POST['nameserver2'];
	$template = $_POST['template'];
	$status = "active";
    
    if(file_exists('../../users/' . $boxctrlusername . '.xml')){
        $errors[] = 'BoxCtrl-VZ username already exists';
    }
    if($boxctrlusername == ''){
        $errors[] = 'BoxCtrl-VZ username is blank';
    }
    if($password == ''){
        $errors[] = 'BoxCtrl-VZ passwords are blank';
    }
	if($ctid == ''){
		$errors[] = 'VPS ID number is blank';
    }
	if($hostname == ''){
		$errors[] = 'VPS hostname is blank';
    }
	if($rootpassword == ''){
		$errors[] = 'VPS root password is blank';
    }
	if($ram == ''){
		$errors[] = 'Dedicated RAM limit is blank';
    }
	if($burstram == ''){
		$errors[] = 'Burst RAM is blank';
    }
	if($diskspace == ''){
		$errors[] = 'Diskspace limit is blank';
    }
	if($nameserver1 == ''){
		$errors[] = 'Nameserver1 is blank';
    }
	if($nameserver2 == ''){
		$errors[] = 'Nameserver2 is blank';
    }	
    if(count($errors) == 0){
        $xml = new SimpleXMLElement('<user></user>');
        $xml->addChild('password', sha1(md5($password)));
		$xml->addChild('status', $status);
		$xml->addChild('ctid', $ctid);
        $xml->asXML('../../users/' . $boxctrlusername . '.xml');
		// CREATE VPS
		shell_exec("../../scripts/create_vps.sh $ctid $hostname $rootpassword $ram $burstram $diskspace $nameserver1 $nameserver2 $template");
		$outputShell = "VPS creation complete. Don't forget to assign the VPS an IP(s) via the <a href='ip-management.php'>IP Management</a> page.";	
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

	<div class="wrapper"><!-- Wrapper -->
	
		<h1 class="logo"><img src="images/logo.png" /></h1>
		<p class="txt_right">Logged in as <strong><?php echo $_SESSION['username']; ?></strong>  <span class="v_line"> | </span> <a href="change-password.php">Change Password</a> <span class="v_line"> | </span> <a href="logout.php"> Logout</a></p>
	
	<!-- Navigation -->
	
		<div class="navigation">
				<ul>
					<li><a href="index.php">Overview</a></li>
					<li><a href="create-new.php" class="active">Create User/VPS</a></li>
					<li><a href="vps-management.php">User/VPS Management</a></li>
					<li><a href="ip-management.php">IP Management</a></li>					
				</ul>			
		</div>
		
		<div class="clear"></div>
	
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
	
		<strong><?php if(@$outputShell){echo "<pre>$outputShell</pre>";} ?></strong>	
		
				<div class="in minitext">
					<h2>Create a New User & VPS</h2>
				</div>

	<div class="in forms">			
    <form method="post" action="">
        <?php
        if(count($errors) > 0){
            echo '<ul>';
            foreach($errors as $e){
                echo '<li>' . $e . '</li>';
            }
            echo '</ul>';
        }
        ?>
        <p><b>BoxCtrl-VZ Username (letters and numbers only, must not start with a number):</b><br /> <input type="text" name="boxctrlusername" value="<?php if(@$boxctrlusername){echo $boxctrlusername;} ?>" class="box"	/></p>
        <p><b>BoxCtrl-VZ Password:</b><br /> <input type="text" name="password" value="<?php if(@$password){echo $password;} ?>" class="box" /></p>
		<p><b>New VPS ID Number:</b><br /> <input type="text" name="ctid" value="<?php if(@$ctid){echo $ctid;} ?>" class="box" /></p>
		<p><b>VPS Hostname:</b><br /> <input type="text" name="hostname" value="<?php if(@$hostname){echo $hostname;} ?>" class="box" /></p>
		<p><b>VPS Root Password:</b><br /> <input type="text" name="rootpassword" value="<?php if(@$rootpassword){echo $rootpassword;} ?>" class="box" /></p>
		<p><b>Guaranteed RAM (in MB, input number only):</b><br /> <input type="text" name="ram" value="<?php if(@$ram){echo $ram;} ?>" class="box" /></p>
		<p><b>Burst RAM (in MB, input number only):</b><br /> <input type="text" name="burstram" value="<?php if(@$burstram){echo $burstram;} ?>" class="box" /></p>
		<p><b>Disk Space (in GB, input number only):</b><br /> <input type="text" name="diskspace" value="<?php if(@$diskspace){echo $diskspace;} ?>" class="box" /></p>
		<p><b>Nameserver #1:</b><br /> <input type="text" value="4.2.2.1" name="nameserver1" class="box" /></p>
		<p><b>Nameserver #2:</b><br /> <input type="text" value="4.2.2.2" name="nameserver2" class="box" /></p>	
		
		<p><b>OS Template:</b><br /> 
		<?php
		$currentdir = '/vz/template/cache';
		$dir = opendir($currentdir);
		echo '<select name="template" class="box">';
		while($username = readdir($dir)){
			if ($username != "." && $username != "..") {
			echo '<option value="'.basename($username, '.tar.gz').'">'.basename($username, '.tar.gz').'</option>';
			}
		}
		echo '</select>'; 
		closedir($dir); 				
		?>		
		
        <br /><br /><p><input type="submit" name="create" value="Create New User and VPS" tabindex="5" class="com_btn" /></p>
    </form>
	<p><strong>Once the setup process is complete, continue to the <a href="ip-management.php">IP Management</a> page to assign the user new IPs.</strong></p>
	<p><em><strong>IMPORTANT:</strong> After clicking on the create button, do not leave this page or hit refresh until it has completed. The process can take up to five minutes to complete.</em></p>
	</div>
		
		</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>