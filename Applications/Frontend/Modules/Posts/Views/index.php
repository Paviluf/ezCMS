<?php

/**
 *  Generate HTML - Posts index
 */

/*$adsHomeTop='<style type="text/css">
.sda-home-top { width: 100%; height: 100px; margin: 5px auto 5px auto; }
@media(min-width: 750px) { .sda-home-top { width: 728px; height: 90px; margin: 5px 0 5px 0; }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Home Top Adp -->
<ins class="adsbygoogle sda-home-top"
     style="display:block"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="5617531818"
     data-ad-format="horizontal"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';*/


$adsHomeBottom = '<style type="text/css">
.sda-home-bottom { width: 100%; height: 100px; margin: 5px auto 5px auto; }
@media(min-width: 750px) { .sda-home-bottom { width: 728px; height: 90px; margin: 5px 0 5px 0; }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Home Bottom Adp -->
<ins class="adsbygoogle sda-home-bottom"
     style="display:block"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="9739660216"
     data-ad-format="horizontal"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

// Generate title - Latest category
$html = \Library\Functions::generateIndexTitle($category);

//$html .= '<div>'.$adsHomeTop.'</div>';

//$i=0;
// Generate index
foreach ($data as $post) {
	if(is_array($post)) {
		// Ads
		//if($i == 3) {
			//$html .= '<div>'.$adsHomeMiddle.'</div>';
		//}

		$postImage = '';
		$postId = $post['id_PK'];
		$postName = $post['name'];
		$postSlug = $post['slug'];
		$postTitle = $post['title'];
		$postExcerpt = $post['excerpt'];
		$postDate = $post['date'];

		$postImageId = '';
		$postImagePath = '';
		$postImageName = '';
		if(!empty($post['imageId'])) {
			$postImageId = $post['imageId'];
			$postImagePath = $post['imagePath'];
			$postImageName = $post['imageName'];
		}

		if(!empty($postImageId)) {
			$postImage = '<img src="/'.MEDIAS_DIRECTORY.$postImagePath.$postImageName.'" alt="'.$postImageName.'" />';
		}

		$html .= '<a href="/';
		if(!empty($postName)) {
			$html .= $postName.'/';
		}
		$html .= $postId.'/'.$postSlug.'">
				<div class="article-title-wrap">
					<div class="article-image">
			      		'.$postImage.'
			    	</div>					
					<div class="article-title">
						<h2>
							'.$postTitle.'
						</h2>
						<div class="article-title-content">
							'.$postExcerpt.'
						</div>
						<div class="article-title-date">
							<em>'.$postDate.'</em>
						</div>
					</div>
				</div>
			</a>';
		//++$i;
	}
}

// Ads
//if(count($data) > 4) {
	$html .= $adsHomeBottom;
//}

// Generate Pagination
$html .= \Library\Functions::paginationCategory($nbOfPostsPerPage, $nbOfPosts, $page, $category);

/********************************/

/*
// Ads
$adsHomeTop='<div id="sda-home-top">
<script type="text/javascript">
	var width = window.innerWidth 
        || document.documentElement.clientWidth 
        || document.body.clientWidth;
google_ad_client = "ca-pub-1581664623738680";
if (width >= 600) {
  google_ad_slot = "9718062616"; 
  google_ad_width = 728; 
  google_ad_height = 90;
} else if (width >= 480) { 
  google_ad_slot = "1239069700"; 
  google_ad_width = 468; 
  google_ad_height = 60;
  document.getElementById("sda-home-top").style.textAlign = "center";
  document.getElementById("sda-home-top").style.height = google_ad_height+"px";
  document.getElementById("sda-home-top").style.margin = "5px 0 5px 0";
} else { 
  google_ad_slot = "3838643415"; 
  google_ad_width = 320; 
  google_ad_height = 100;
  document.getElementById("sda-home-top").style.textAlign = "center";
  document.getElementById("sda-home-top").style.height = google_ad_height+"px";
  document.getElementById("sda-home-top").style.margin = "5px 0 5px 0";
}
</script>
<!-- Ezoden - Post Top -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';
*/

/*
.ezoden-post-top-adp { width: 320px; height: 50px; }
@media(min-width: 468px) { .ezoden-post-top-adp { width: 468px; height: 60px; } }
@media(min-width: 728px) { .ezoden-post-top-adp { width: 728px; height: 90px; margin: 0 0 0 -10px; } }
*/
