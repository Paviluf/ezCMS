<?php
/**
 * Install Index
 */

function slugify($text) { 
	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	// trim
	$text = trim($text, '-');
	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	// lowercase
	$text = strtolower($text);
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);
	if (empty($text)) {
		return 'n-a';
	}
	return $text;
}

// Root path (eg. /home/user/ezCMS/)
if(!defined('ROOT_PATH')) {
	// Don't works on some Host
	//define('ROOT_PATH', realpath($_SERVER['DOCUMENT_ROOT']).'/../');

	$rootPath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_FILENAME']), 0, -3));
	define('ROOT_PATH', $rootPath.'/');
	unset($rootPath);
}

if(!defined('SALT')) {
	define('SALT', 'eL|m?F=/APc1T:zX=RAE|}]cl@hF>hJoGMT,d|:m9wB?c+A5IDH(e{->ed.-J8q/');
}

// System name
$sysName = 'ezCMS';

// Set config file path
$filePath = ROOT_PATH.'Library/Config/dev.xml';
if(!file_exists($filePath)) {
	$filePath = ROOT_PATH.'Library/Config/prod.xml';
}

// Check if the system is already installed
if(file_exists($filePath) && empty($_POST)) {
	$content = $sysName." seems to be already installed !";
}
else {
	require('install.php');
}

/*
 * HTML page
 */
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="/install/style.css" />
		<title><?php echo $sysName; ?> Installation</title>
	</head>
	
	<body>
		<div class="main-wrapper">
			<header>
				<div class="container-inner">
					<h1><?php echo $sysName; ?> INSTALLATION</h1>
				</div>
			</header>

			<div class="container-inner">	
				<div class="main">	
					<?php echo $content; ?>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="/install/scripts.js"></script>
	</body>
</html>