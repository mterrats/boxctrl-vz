<?php
session_start();
if(!file_exists('../../superusers/' . $_SESSION['username'] . '.xml') || empty($_SESSION['username'])){
    header('Location: login.php');
    die;
}

$outputShell = "";
if(isset($_POST['change'])){
	$ipaddress = $_POST['ipaddress'];
	$assignto = $_POST['assignto'];
	
	// DELETE AN IP THAT HAS ALREADY BEEN ASSIGNED TO A CONTAINER
	if (empty($assignto)){
		$ip_xml = new SimpleXMLElement($ipaddress, 0, true);
		$user_file = $ip_xml->ownership.".xml";
		if(file_exists("../../users/$user_file")){
			$user_xml = new SimpleXMLElement("../../users/$user_file", 0, true);
			$ctid = $user_xml->ctid;
			$ipaddy = basename($ipaddress, '.xml');
			/**
			$ipaddydel = 'x'.$ipaddy; // Remove IP from user's XML file
			unset($user_xml->ipaddresses->$ipaddydel); // Remove IP from user's XML file
			$user_xml->asXML("../../users/$user_file"); // Remove IP from user's XML file
			**/
			$outputShell = shell_exec("sudo vzctl set $ctid --ipdel $ipaddy --save");
		}
		// UPDATE IP.XML FILE WITH NEW DATA
		$xml = new SimpleXMLElement($ipaddress, 0, true);
			if($xml){
			$xml->ownership = $assignto;
			$xml->asXML($ipaddress);
			}
	}
	// ADD AN IP TO A CONTAINER
	elseif($assignto){
		$user_xml = new SimpleXMLElement("../../users/$assignto.xml", 0, true);
		$ctid = $user_xml->ctid;
		$ipaddy = basename($ipaddress, '.xml');
		/**
		$user_xml->ipaddresses->addChild("x$ipaddy", $ipaddy); // Write IP to user's XML file
		$user_xml->asXML("../../users/$assignto.xml"); // Write IP to user's XML file
		**/
		$outputShell = shell_exec("sudo vzctl set $ctid --ipadd $ipaddy --save");
		// UPDATE IP.XML FILE WITH NEW DATA
		$xml = new SimpleXMLElement($ipaddress, 0, true);
			if($xml){
			$xml->ownership = $assignto;
			$xml->asXML($ipaddress);
			}
	}

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
					<li><a href="vps-management.php">User/VPS Management</a></li>
					<li><a href="ip-management.php" class="active">IP Management</a></li>					
				</ul>			
		</div>
		
		<div class="clear"></div>
	
		<div class="content"><!-- content -->
		
	<!-- Subnav -->
	
		<div class="toplinks">	
				<ul>		

					<li><a href="#"><strong>IP Management</strong></a></li>
					<li><a href="add-ip-range.php">Add IP Range</a></li>						
				</ul>	
		</div>	
		
				<div class="in minitext">
					<h2>IP Management</h2>
					<p>This page is a modular plugin for BoxCtrl-VZ. Creating a new user/VPS or deleting a current user/VPS will not add/remove them from their IP assignment on this page. You should assign users to IPs after creating their accounts and unassign them before removing their accounts. The table below can be sorted by clicking on either the "IP" or "Ownership" box.</p>
				</div>

	<div class="in forms">
	<strong><?php if($outputShell){echo "<pre>$outputShell</pre>";} ?></strong>
	<table width="100%" border="0" class="sortable">
		<tr>
			<td style="background-color:#eaeaea;" ><b>IP</b></td>
			<td style="background-color:#eaeaea;" ><b>Ownership</b></td>
			<td style="background-color:#eaeaea;" ><b>Manage</b></td>		
		</tr>
		<?php
		$files = glob('../../ipaddresses/*.xml');
		foreach($files as $ipaddress){
			$xml = new SimpleXMLElement($ipaddress, 0, true);
			echo   "<tr>";
			echo   '<td>'.basename($ipaddress, '.xml').'</td>';
			echo   '<td>'.$xml->ownership.'</td>';
			echo   "<td>"; //////// Begin Change Assignment Form ///////////
			echo   "<form method='post' action=''>";
			echo   "<input type='hidden' name='ipaddress' value='$ipaddress' />";		
			$currentdir = '../../users';
			$dir = opendir($currentdir);	 
			echo '<select name="assignto">'; 
			echo '<option value="">Unassiagn &raquo;</option>'; 
			while($username = readdir($dir)){ 
				if ($username != "." && $username != "..") {
				echo '<option value="'.basename($username, '.xml').'">'.basename($username, '.xml').'</option>'; 
				}
			} 
			echo '</select>'; 
			closedir($dir); 				
			echo   "<input type='submit' name='change' value='Select' />";
			echo   "</form>";
			echo   "</td>"; //////// End Change Assignment Form ///////////
			echo   "</tr>";	
		}
		?>
	</table>
	
	</div>
		
		</div><!-- content -->
		
		<p class="footer">&copy; BoxCtrl-VZ</p>
	</div><!-- Wrapper -->
</body>
</html>