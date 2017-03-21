<?php

// if(!isset($summary) || empty($summary)) {
	
// }
// else {

// }

$adsSidebar = '<style type="text/css">
.sda-sidebar { width: 300px; margin: 5px auto 5px auto; }
@media(min-width: 750px) { .sda-sidebar { width: 300px; height: 250px; margin: 0 auto 0 auto; }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Sidebar Adp -->
<ins class="adsbygoogle sda-sidebar"
     style="display:block"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="6944646619"
     data-ad-format="rectangle"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';


$html .= '<div class="box">'.$adsSidebar.'</div>';
// Generate Summary
if(isset($summary) && !empty($summary)) {
	$html .= \Library\Functions::buildSumary($summary, $id_PK, $name);		
}

//if(!isset($summary) || empty($summary)) {
//	$html .= '<div class="box">'.$adsSidebar.'</div>';	
//}

// forum posts
$PostsForumData = new \Library\Models\ForumManager;
$postsForum = $PostsForumData->latestPostsFromForum(15);

$html .= '<div class="box posts-from-forum"><h1>Forum Posts</h1>';
$topic_titles = '';
$i = 0;
while($postForum = $postsForum->fetch()) {
	$forum_id = $postForum['forum_id'];
	$topic_id = $postForum['topic_id'];
	$topic_title = $title_html = ucfirst($postForum['topic_title']);
	
	if(strlen($topic_title) >= 36) {
		$topic_title = \Library\Functions::htmlToStr($topic_title, 36, false, true);
	}
	
	//$live_forum_topic_title_color = $i%2 ? 'posts-from-forum-topic-alt' : 'posts-from-forum-topic';
	
	$topic_titles .= '<div class="posts-from-forum-topic"><a href="http://forum.ezoden.com/viewtopic.php?f='.$forum_id.'&amp;t='.$topic_id.'" target="_blank" title="'.$title_html.'">'.$topic_title.'</a></div>';

	++$i;	
}
$html .= '<div class="posts-from-forum-wrap">'.$topic_titles.'</div></div>';

// Support		
$html .= '<div class="box support"><h1>Support</h1><p><strong>If you enjoy what I do or block the ads, you can give a little back with a <a href="/2/support">small donation.</a></strong></p></div>';



/******************************/

// || (!isset($index) || !$index) && (!isset($summary) || empty($summary))
//if(isset($index) && $index) {
//	$html .= $adsSidebarBottom;	
//}


/* // Support
$html .= '<a href="/2/support">
	<div class="supportWrap">
		<div class="support">
			<div class="supportTxt">
				<div class="vertical-align-wrapper">
					<div class="vertical-align-inner">	
						<strong>If you enjoy what I do, you can give a little back with a small donation</strong>
					</div>
				</div>
			</div>
		</div>
	</div>
</a>
';
*/ 

/* OLD SUPPORT
$html .= '<a href="/2/support">
	<div class="supportWrap">
		<div class="support">
			<div class="supportImg">
				<div class="vertical-align-wrapper">
					<div class="vertical-align-inner">
						<img src="/medias/2014/12/support.jpg" alt="Support" title="Support '.$siteName.'. Image : Susanne Nilsson - Cup of coffee - Flickr" />
					</div>
				</div>
		 	</div>
			<div class="supportTxt">
				<div class="vertical-align-wrapper">
					<div class="vertical-align-inner">	
						<span class="supportSupport" >Support my work and</span><br />		
						<span class="supportSiteName" >'.$siteName.'</span><br />
						<span class="supportPrice" >With a coffee</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</a>
';
*/


/* ADS ASYNC
$adsSidebar = '<style>
				.ezoden-sidebar-adp { width: 300px; height: 250px; }
				@media(min-width: 500px) { .ezoden-sidebar-adp { width: 300px; height: 250px; } }
				@media(min-width: 800px) { .ezoden-sidebar-adp { width: 300px; height: 250px; } }
				</style>
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Ezoden - Sidebar Adp -->
				<ins class="adsbygoogle ezoden-sidebar-adp"
				     style="display:inline-block"
				     data-ad-client="ca-pub-1581664623738680"
				     data-ad-slot="6944646619"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
				'; 
*/

/*
// Ads
$adsSidebar = '<div style="width:300px; margin: 10px 0 10px 0;">';
/*if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 3, 2) == 'FR') {
	$adsSidebar .= '<SCRIPT charset="utf-8" type="text/javascript" src="http://ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&MarketPlace=FR&ID=V20070822%2FFR%2Fwen-21%2F8009%2F570716f5-06a9-4474-8f4e-0fb02005d153&Operation=GetScriptTemplate"> </SCRIPT> <NOSCRIPT><A HREF="http://ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&MarketPlace=FR&ID=V20070822%2FFR%2Fwen-21%2F8009%2F570716f5-06a9-4474-8f4e-0fb02005d153&Operation=NoScript">Widgets Amazon.fr</A></NOSCRIPT>';
} 
else {
	$adsSidebar .= '<script type="text/javascript">
					    google_ad_client = "ca-pub-1581664623738680";
					    google_ad_slot = "5670086445";
					    google_ad_width = 300;
					    google_ad_height = 250;
					</script>
					<!-- Ezoden - Sidebar 300x250 -->
					<script type="text/javascript"
					src="//pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>';
//}
$adsSidebar .= '</div>';

$adsSidebarBottom = '<div style="width:300px; margin: 0 auto 10px auto;">
				            <script type="text/javascript">
								    google_ad_client = "ca-pub-1581664623738680";
								    google_ad_slot = "5315376610";
								    google_ad_width = 300;
								    google_ad_height = 250;
								</script>
								<!-- Ezoden - Sidebar Bottom 300x250 -->
								<script type="text/javascript"
								src="//pagead2.googlesyndication.com/pagead/show_ads.js">
								</script>
							</div>';

//if(isset($index) && $index) {
	//$html .= $adsSidebar;
//}
*/