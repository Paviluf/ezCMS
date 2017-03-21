<?php

/**
 * HTML Template Layout
 * Build Full HTML Page
 */

//HTML Full Page
$html = '';

// DEBUG
if(DEV) {
	$html .= '<!-- Call MySQL : '.$_SESSION['mysql'].'-->';
}

// CSS
$cssPath = 'templates/'.mb_strtolower($appName).'/'.mb_strtolower($template).'/css/';
$css = $cssPath.'style.css';
$css = '/'.$css.'?ver='.filemtime(WEB_PATH.$css); 
$cssCustom = $cssPath.'custom.css';
$cssCustom = '/'.$cssCustom.'?ver='.filemtime(WEB_PATH.$cssCustom); 

// JS
$jsPath =  'js/';

$scripts = $jsPath.'scripts.js';
$analytics = $jsPath.'analytics.js';
$advertisement = $jsPath.'advertisement.js';

$scripts = '/'.$scripts.'?ver='.filemtime(WEB_PATH.$scripts); 
$analytics = '/'.$analytics.'?ver='.filemtime(WEB_PATH.$analytics); 
$advertisement = '/'.$advertisement.'?ver='.filemtime(WEB_PATH.$advertisement); 

$scriptsSrcArray = array($scripts, $analytics);
$scriptsArray = array();

// If client isn't a Bot, adblock checking
$adblockBlockMsg = '';
if(!DEV) {
	if(!$isBot) {	
		$adblockBlockMsg = '<div id="msgAdblock">
							</div>';

		$scriptsSrcArray[] = $advertisement;
		$scriptsArray[] = 'setTimeout(adblockBlock, 2500);';
	}
}

// Create variable ($js) with all javascript
$js = '';
foreach($scriptsSrcArray as $src) {
	$js .= '<script type="text/javascript" src="'.$src.'"></script>';
}
foreach($scriptsArray as $script) {
	$js .= '<script type="text/javascript">'.$script.'</script>';
}

// Build Full HTML Page
$html .= '<!DOCTYPE html>
			<html>
				<head>
					<meta charset="UTF-8" />
					<!-- <meta name="viewport" content="initial-scale=1.0"> -->
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<link rel="stylesheet" href="'.$css.'" />
					<link rel="stylesheet" href="'.$cssCustom.'" />
					<link href="http://fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css">
					<title>'.$pageTitle.' - '.$siteName.'</title>
					<meta name="description" content="'.$pageDescription.'" />
					<link rel="icon" type="image/x-icon" href="/medias/favicon.ico" />
					<link rel="icon" type="image/png" href="/medias/favicon.png" />
				</head>

				<body>
					<div class="main-wrapper">
						<header>
							<div class="container-inner">
								<div class="header-title_nav-toggle">
									<a href="'.$host.'"><h1>'.$siteName.'</h1></a>
									<div class="nav-toggle flexbox-container" onClick="toggleHeaderMenu(\'menu-header\')">
										  	<div class="bar-nav-toggle"></div>
											<div class="bar-nav-toggle"></div>
											<div class="bar-nav-toggle"></div>
									</div>
								</div>
								<nav class="menu-header-a" id="menu-header">
									<ul>
										'.$menu.'
									</ul>
								</nav>
							</div>
						</header>						
						<div class="container-inner">	
							<div class="version">'.VERSION.'</div>	 
							<div class="two-columns">
								<div class="sidebar">
				            	'.$adblockBlockMsg.$sidebar.'
				            </div>
				            <div class="box content">
				            	'.$carousel.$content.'
				            </div>
						    </div>
						</div>
						<footer>
							'.$footer.'
						</footer>
					</div>
					'.$js.'	
				</body>
			</html>';


/********************************************/

/*$adsMainTop='<div id="sda-main-top">
<script type="text/javascript">
	var width = window.innerWidth 
        || document.documentElement.clientWidth 
        || document.body.clientWidth;
google_ad_client = "ca-pub-1581664623738680";
if (width >= 750) {
  google_ad_slot = "5218588992"; 
  google_ad_width = 728; 
  google_ad_height = 90;
  document.getElementById("sda-main-top").style.textAlign = "center";
  document.getElementById("sda-main-top").style.width = "100%";
  document.getElementById("sda-main-top").style.margin = "0";
} 
else { 
  google_ad_slot = "3838643415"; 
  google_ad_width = 320; 
  google_ad_height = 100;
  document.getElementById("sda-main-top").style.textAlign = "center";
  document.getElementById("sda-main-top").style.width = "100%";
  document.getElementById("sda-main-top").style.margin = "0";
}
</script>
<!-- Ezoden - Post Main -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';
*/


/*

<!-- containing element to implement centering trick on -->
<div class="vertical-align-wrapper">
	<!-- element to center -->
  	<div class="vertical-align-inner">
  		<div class="bar-nav-toggle"></div>
		<div class="bar-nav-toggle"></div>
		<div class="bar-nav-toggle"></div>
  	</div>
</div>

<div style="position:absolute;top:-8px;right:15px;color:#AAA;font-size:0.85em;font-style:italic;font-weight:bold">'.VERSION.'</div>	

*/