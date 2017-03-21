<?php

/**
 * Create a sitemap
 */

// Base config
require('../Library/Config/base.php');

// Get all active Posts
$PostsData = new \Library\Models\PostsManager(\Library\Config::getSetting('tablePrefix')); 
$postsArray = $PostsData->allActivePosts()->fetchAll();

// Generate sitemap
$urls = '';
$LastMod = '';
foreach ($postsArray as $post) {
	// Build URL
	$url = 'http://'.$_SERVER['SERVER_NAME'].'/';
	if(isset($post['name']) && !empty($post['name'])) {
		$url .= $post['name'].'/';
	}
	$url .= $post['id_PK'].'/'.$post['slug'];

	// Post date
	if($post['dateModified'] > $post['dateCreated']) {
		$date = $post['dateModified'];
	}
	else {
		$date = $post['dateCreated'];
	}
	$date = date('Y-m-d\Th:i:s', strtotime($date)).'+00:00';

	// Last modification date
	if(empty($LastMod)) {
		$LastMod = $date;
	}

	// Build <url>...</url>
 	$urls .= '<url>
			    <loc>'.$url.'</loc>
			    <lastmod>'.$date.'</lastmod>
			  </url>';
 } 

// Send Header XML
header('Content-type: application/xml');
// Display sitemap
echo '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
			<url>
				<loc>http://'.$_SERVER['SERVER_NAME'].'/</loc>
				<lastmod>'.$LastMod.'</lastmod>
				<changefreq>daily</changefreq>
			</url>
			'.$urls.'
		</urlset>';