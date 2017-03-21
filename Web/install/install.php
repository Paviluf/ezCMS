<?php
/**
 * Install Script
 *
 * Create config file
 * Create database
 * Create Admin
 * Set fisrt data
 */

$content = '';
$errorMsg = array();

$formVars = array('dbName' => '',
				'dbUser' => '',
				'dbPwd' => '',
				'dbHost' => '',
				'tablePrefix' => '',
				'siteName' => '',
				'name' => '',
				'pwd' => '',
				'pwdCheck' => '',
				'mail' => '',
				'dev' => '');

if(isset($_POST['send'])) {
	foreach ($_POST as $k => $v) {
		if($k != 'tablePrefix' && $k != 'dbPwd' && empty($v) && empty($errorMsg)) {
			$errorMsg[] = 'You have to fill every fields.';
		}

		if(isset($formVars[$k]) && $k != 'pwd' && $k != 'pwdCheck' && $k != 'dbPwd') {
			if('dev' == $k) {
				$formVars['dev'] = 'checked';
			}
			else {
				$formVars[$k] = $v;
			}
		}
	}

	if(empty($errorMsg)) {
		if($_POST['pwd'] != $_POST['pwdCheck']) {
			$errorMsg[] = 'You have to type 2 times the same password.';
		}

		if(mb_strlen($_POST['pwd']) < 6 || mb_strlen($_POST['pwd']) > 16) {
			$errorMsg[] = 'Your password must be at least 6 characters and 16 characters max.';
		}

		if(!preg_match('/^[a-zA-Z0-9]+$/u', $_POST['name'])) {
			$errorMsg[] = 'Only alphanumeric characters allowed for user name.';
		}

		if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
	  		$errorMsg[] = 'Invalid email';
		}
	}

	if(!empty($errorMsg)) {
		foreach ($errorMsg as $k => $v) {
			$content .= '<p class="error">'.$v.'</p>';
		}
	}
}

if(!isset($_POST['send']) || (isset($_POST['send']) && !empty($errorMsg))) {
	$content .= '<h3>Please complete the form below to start the installation.</h3>
		<form method="post" action="index.php">
			<table>
				<caption>Database Informations</caption>
				<tr>
					<td>Database Name</td>
					<td><input type="text" name="dbName" value="'.$formVars['dbName'].'" /></td>
					<td>Database name in which '.$sysName.' is installed.</td>
				</tr>
				<tr>
					<td>MySQL login</td>
					<td><input type="text" name="dbUser" value="'.$formVars['dbUser'].'" /></td>
					<td>Your MySQL login.</td>
				</tr>
				<tr>
					<td>MySQL Password</td>
					<td><input type="password" name="dbPwd" value="'.$formVars['dbPwd'].'" /></td>
					<td>Your MySQL Password.</td>
				</tr>
				<tr>
					<td>Database host</td>
					<td><input type="text" name="dbHost" value="'.$formVars['dbHost'].'" /></td>
					<td>Generally localhost.</td>
				</tr>
				<tr>
					<td>Table prefix</td>
					<td><input type="text" name="tablePrefix" value="'.$formVars['tablePrefix'].'" /></td>
					<td>Change this if you install '.$sysName.' multiple times in the same database.</td>
				</tr>
			</table>
			<table>
				<caption>Site and User Informations</caption>
				<tr>
					<td>Site Title</td>
					<td><input type="text" name="siteName" value="'.$formVars['siteName'].'" /></td>
					<td>The name of your Website.</td>
				</tr>
				<tr>
					<td>User name</td>
					<td><input type="text" name="name" value="'.$formVars['name'].'" /></td>
					<td>Choose your user name. Only alphanumeric characters.</td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="pwd" value="'.$formVars['pwd'].'" /></td>
					<td>Choose your password. Between 6 and 16 characters.</td>
				</tr>
				<tr>
					<td>Retype your password</td>
					<td><input type="password" name="pwdCheck" value="'.$formVars['pwdCheck'].'" /></td>
					<td>Retype your password</td>
				</tr>
				<tr>
					<td>eMail</td>
					<td><input type="text" name="mail" value="'.$formVars['mail'].'" /></td>
					<td>Your eMail address</td>
				</tr>
				<tr>
					<td>Installation Type</td>
					<td><input type="checkbox" name="dev" '.$formVars['dev'].' /></td>
					<td>If you install '.$sysName.' in a developpement environment check this case.</td>
				</tr>
			</table>
		<input class="send" type="submit" name="send" value="Install '.$sysName.'" />
	</form>';
}
else {
	unset($_POST['send']);
	unset($_POST['pwdCheck']);
	if(isset($_POST['dev'])) {
		$configFileName = 'dev.xml';
		unset($_POST['dev']);
	}
	else {
		$configFileName = 'prod.xml';
	}

	$userData = array();
	$settingsData = array();
	foreach($_POST as $k => $v) {
		if($k == 'name' || $k == 'mail' || $k == 'pwd') {
			if($k == 'name') {
				$v = mb_strtolower($v);
			}
			$userData[$k] = $v;
		}
		else {
			$settingsData[$k] = $v;
		}
	}

	$configFile = fopen(ROOT_PATH.'Library/Config/'.$configFileName, 'w');
	$write = '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL.'<settings>'.PHP_EOL;
	$write .= "\t";
	$write .= '<setting var="sysName" value="'.$sysName.'" />'.PHP_EOL;
	foreach($settingsData as $k => $v) {
		$write .= "\t";
		$write .= '<setting var="'.$k.'" value="'.$v.'" />'.PHP_EOL;
	}
	$write .= '</settings>';
	fwrite($configFile, $write);
	fclose($configFile);

	require("connexion.php");
	require("database.php");

	$fields = "";
	$values = "";
	foreach($userData as $field => $v) {
		$fields .= $field.",";
		if($field == "pwd") {
			$values .= "'".sha1(SALT.md5($v.SALT).sha1(SALT))."',";
		}
		else {
			$values .= "'".$v."',";
		}
	}

	$fields .= "registrationDate,status,type,permissions";
	$values .= "NOW(),1,'admin',1";

	$sql = "INSERT INTO ".$tablePrefix."users($fields) VALUES($values)";

//$dsn = "mysql:host=".$dbHost.";dbname=".$dbName.";charset=utf8";

	$dbh->exec("USE `$dbName`;");
	$dbh->exec($sql);

	require('firstdata.php');

	//foreach ($firstData as $k => $sql) {
    //	$dbh->exec($sql);
    //}

	$content = '<h3><em>'.$_POST['siteName'].'</em> is now installed !</h3>
				<p class="important">
					For security reasons I recommend you to delete the "install" directory :<br />
					<em>"'.realpath($_SERVER['DOCUMENT_ROOT']).'/install"</em>
				</p>
				<p>
					You can access to <em>'.$_POST['siteName'].'</em> with its url : <a href="http://'.$_SERVER['SERVER_NAME'].'" target="_blank">http://'.$_SERVER['SERVER_NAME'].'</a>
				</p>';
}
