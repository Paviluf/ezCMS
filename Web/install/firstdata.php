<?php

// Custom first data
if(file_exists('custom.php')) {
	require 'custom.php';
}
// Generic first data
else {
	$firstData = array();

	// News Menu
	$firstData[] = 'INSERT INTO '.$tablePrefix.'menus(name, mOrder, url) VALUES("news", 1, "/")';

	// News Category
	$firstData[] = 'INSERT INTO '.$tablePrefix.'categories(name, status) VALUES("news", 1)';

	// Options
	$firstData[] = 'INSERT INTO '.$tablePrefix.'options(name, value) VALUES("nbOfPostsPerPage", "6")';
	$firstData[] = 'INSERT INTO '.$tablePrefix.'options(name, value) VALUES("homeCategory", "news")';
	$firstData[] = 'INSERT INTO '.$tablePrefix.'options(name, value) VALUES("homeTitle", "Home")';
	$firstData[] = 'INSERT INTO '.$tablePrefix.'options(name, value) VALUES("homeDescription", "News")';
	$firstData[] = 'INSERT INTO '.$tablePrefix.'options(name, value) VALUES("template", "default")';

	// Post one (news)
	$firstData[] = 'INSERT INTO '.$tablePrefix.'posts(userId_FK, title, content, imageId, slug, status, dateCreated, commentStatus) VALUES(1, "Welcome on '.$_POST['siteName'].' powered by '.$sysName.'", "<p>This is your first post. You can edit it or delete it and start a new post !</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>", 1, "first-post", 1, NOW(), 1)';

	// Set category to post one (news) 
	$firstData[] = 'INSERT INTO '.$tablePrefix.'postsCatR(postId_FK, categoryId_FK) VALUES(1, 1)';

	// One media (welcome.jpg)
	$firstData[] = 'INSERT INTO '.$tablePrefix.'medias(filePath, fileName, source, sourceUrl type, userId_FK, status, dateCreated) VALUES("2014/12/", "welcome.jpg", "halfrain - Welcome - Flickr", "http://www.flickr.com/photos/halfrain/15791245371", jpg", "1", "ok", NOW())';
}