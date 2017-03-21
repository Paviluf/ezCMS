<?php

/**
 * HTML Template Layout
 * Build Full HTML Page
 */

//HTML Full Page
$html = '';

// CSS
$cssPath = 'templates/'.mb_strtolower($appName).'/'.mb_strtolower($template).'/css/';
$css = $cssPath.'style.css';
$css = '/'.$css.'?ver='.filemtime(WEB_PATH.$css); 

// JS
$jsPath =  'js/';
$scripts = $jsPath.'scripts.js';
$scripts = '/'.$scripts.'?ver='.filemtime(WEB_PATH.$scripts);


// Build Full HTML Page
$html = '<!DOCTYPE html>
			<html>
				<head>
					<meta charset="UTF-8" />
					<link rel="stylesheet" href="'.$css.'" />
					<title>'.$siteName.'</title>
					<meta name="description" content='.$siteName.' />
				</head>

				<body>
					<div class="main-wrapper">
						<header>
							<div class="container-inner">
								<a href="'.$host.'/admin/"><h1>'.$siteName.' Admin</h1></a>
							</div>
						</header>
						<div class="container-inner">							
				            <div class="content">
				            	'.$content.'
				            </div>					    
					        <div class="sidebar">
					        	<div><a href="/admin/posts">Posts</a></div>
					        	<div><a href="/admin/medias">Medias</a></div>
					        </div>
						</div>
						<footer>
							'.$footer.'
						</footer>
					</div>
					<script type="text/javascript" src="'.$scripts.'"></script>
				</body>
			</html>';