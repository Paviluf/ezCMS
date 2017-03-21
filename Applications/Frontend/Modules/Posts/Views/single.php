<?php

/**
 *  Generate HTML - Single Post
 */

/*
.ezoden-post-top-adp { width: 320px; height: 50px; }
@media(min-width: 468px) { .ezoden-post-top-adp { width: 468px; height: 60px; } }
@media(min-width: 728px) { .ezoden-post-top-adp { width: 728px; height: 90px; margin: 0 0 0 -10px; } }
*/

/*$adsPostTopLinks='<style type="text/css">
.sda-post-top-links { display:block; width:728px; height:15px; }
@media (max-width: 750px) { .sda-post-top-links { display: none; } }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Post Top 728x15 -->
<ins class="adsbygoogle sda-post-top-links"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="8288862613"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';*/

/*$adsPostBottomLinks= '<style type="text/css">
.sda-post-bottom-links { display:block; width:728px; height:15px; margin: 0 0 25px 0; }
@media (max-width: 750px) { .sda-post-bottom-links { display: none; } }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Post Bottom 728x15 -->
<ins class="adsbygoogle sda-post-bottom-links"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="2203120007"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';*/

$adsPostTop='<style type="text/css">
.sda-post-top { width: 310px; height: 250px; margin: 5px auto 5px auto; }
@media(min-width: 750px) { .sda-post-top { float: left; width: 336px; height: 280px; margin: 5px 15px 15px 0; }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Post Top Adp -->
<ins class="adsbygoogle sda-post-top"
     style="display:block"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="7094265015"
     data-ad-format="rectangle"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

$adsPostMiddle='<style type="text/css">
.sda-post-middle { width: 310px; height: 100px; margin: 5px 0 5px 0; }
@media(min-width: 750px) { .sda-post-middle { width: 728px; height: 90px; margin: 5px 0 5px 0; }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Post Middle Adp -->
<ins class="adsbygoogle sda-post-middle"
     style="display:block"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="4699201818"
     data-ad-format="horizontal"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

$adsPostBottom='<style type="text/css">
.sda-post-bottom { width: 100%; height: 100px; margin: 5px auto 5px auto; }
@media(min-width: 750px) { .sda-post-bottom { width: 728px; height: 90px; margin: 5px 0 5px 0; }
</style>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Ezoden - Post Bottom Adp -->
<ins class="adsbygoogle sda-post-bottom"
     style="display:block"
     data-ad-client="ca-pub-1581664623738680"
     data-ad-slot="3156273015"
     data-ad-format="horizontal"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';


if(isset($parentTitle) && !empty($parentTitle)) {
	// Generate Post Title (parent title + title)
	$html = \Library\Functions::generateSingleParentTitle($parentTitle, $title);
}
else {
	// Generate Post Title
	$html = \Library\Functions::generateSingleTitle($title);
}

// Open <article> tag
$html .= '<article>';

// Add Ads if needed according to post length
$contentArray = explode('</p>', $content);
$nbOfWords = count(explode(' ', $content));
$nbOfImg = count(explode('<img', $content));
$nbOfPTags = count($contentArray);
$rand = rand(2, round($nbOfPTags/1.5));

$content = '';

foreach ($contentArray as $k => $v) {
	if(!empty(trim($contentArray[$k]))) {

		if($nbOfWords > 400 || $nbOfImg >= 2) {
			if($k == $rand) {
				$content .= $adsPostMiddle;
			}
		}
		$content .= $contentArray[$k].'</p>';
	}		
}

$content .= $adsPostBottom;

//if($nbOfWords > 200 || $nbOfImg >= 2) {
	//$content .= $adsPostBottomLinks;
	//$content .= $adsPostBottom;
//}
//else if($nbOfWords > 80) {
	//$content .= $adsPostBottomLinks;
//}

$html .= '<div>'.$content.'</div>';

// Close </article> tag
$html .= '</article>';

// Generate Post Metadata - Open <div> tag for "postMetaWrap"
$html .= '<div class="postMetaWrap">';

// Open <div> tag for "postMeta"
$html .= '<div class="postMeta">';

// Generate Post Author and Date
if(isset($author) && !empty($author)) {
	$html .= '<div>By '.$author;
	if(isset($date) && !empty($date)) {
		$html .= ' - '.date('m/d/Y g:ia', strtotime($date));
	}
	$html .= '</div>';
}

// Generate Post Source
if(isset($source) && !empty($source)) {
	if(isset($sourceUrl) && !empty($sourceUrl)) {
		$source = '<a href="'.$sourceUrl.'" target="_blank">'.$source.'</a>';
	}
	$html .= '<div>Source : '.$source.'</div>';
}

// Generate Post Images Sources
if(isset($imageSource) && !empty($imageSource)) {
	$html .= '<div>';
	if(mb_substr_count($imageSource, ', ')>= 1) {
		$html .= 'Images Sources : ';
	}
	else {
		$html .= 'Image Source : ';
	}
	$html .= $imageSource.'</div>';
}

// Close </div> tags for "postMetaWrap" and "postMeta"
$html .= '</div></div>';

// Generate Prev and Next Posts
if(isset($category) && !empty($category)) {
	// Open <div> tag for "post-prev-next"
	$html .= '<div class="post-prev-next">';

	// Generate Prev Post
	$html .= '<a href="/';
	if(isset($prevPost) && !empty($prevPost)) {
		if(isset($name) && !empty($name)) {
			$html .= $name.'/';
		}
		$html .= $prevPost['id_PK'].'/'.$prevPost['slug'].'">';
		$prev = $prevPost['title'];	
	}
	else {
		if($category != 'news') {
			$html .= $category.'/';
		}
		$html .= '">';
		$prev = ucfirst($category);
	}


	$html .= '<div class="post-prev">
				<div class="post-label">
					<div class="post-label-left-bottom">
						Previous
					</div>
				</div>
				<div class="post-title">
					'.$prev.'
				</div>
			</div>
		</a>';

	// Generate Next Post
	if((isset($nextPost) && !empty($nextPost)) || (isset($prevPost) && !empty($prevPost))) {
		$html .= '<a href="/';
			if(isset($nextPost) && !empty($nextPost)) {
				if(isset($name) && !empty($name)) {
					$html .= $name.'/';
				}
				$html .= $nextPost['id_PK'].'/'.$nextPost['slug'].'">';
				$next = $nextPost['title'];
			}
			else {
				if($category != 'news') {
					$html .= $category.'/';
				}
				$html .= '">';
				$next = ucfirst($category);
			}
		
		$html .= '<div class="post-next">
					<div class="post-label">
						<div class="post-label-right-bottom">
							Next
						</div>
					</div>
					<div class="post-title">
						'.$next.'
					</div>
				</div>
			</a>';
	}

	// Close </div> tag for "post-prev-next"
	$html .= '</div>';
}

//$html .= $adsPostAfter;


/**************************************/

//if($nbOfPTags >= 10 || ($nbOfPTags > 6 && count(explode('<img', $content)) >= 2)) {


//if($k == $nbOfPTags-2 && ($nbOfPTags > 5 || (count(explode('<img', $content)) > 1))) {
			//	$content .= $adsPostBottom;
			//}

			//if($nbOfWords > 200 || $nbOfImg >= 2) {
				/*// Add Ads if needed according to post length - Post Bottom
				if(isset($contentArray[$nbOfPTags-2]) 
					&& (count(explode('<img', $contentArray[$nbOfPTags-2])) == 1) 
					&& isset($contentArray[$nbOfPTags-3])
					&& (count(explode('<img', $contentArray[$nbOfPTags-3])) == 1) 
					&& $k == $nbOfPTags-3 
					&& ($nbOfPTags > 7)
					&& count(explode(' ', $contentArray[$nbOfPTags-2])) + count(explode(' ', $contentArray[$nbOfPTags-3])) > 80
					) {
					$content .= $adsPostBottomRight;
					$adOK=false;
				}

				$content .= $contentArray[$k].'</p>';

				// ($nbOfPTags > 7)
				if($k == $nbOfPTags-2 && $adOK) {
					$content .= $adsPostBottomCenter;
				}*/
				//$content .= $contentArray[$k].'</p>';

#$testAdRight300x250='<div style="float:right;width:300px;height:250px;margin:0 0 10px 10px;background-color:grey;"></div>';

/*
$adsPostBottomRight='<div id="sda-post-bottom-right">
<script type="text/javascript">
	var width = window.innerWidth 
        || document.documentElement.clientWidth 
        || document.body.clientWidth;
google_ad_client = "ca-pub-1581664623738680";
if (width >= 600) {
  google_ad_slot = "8907234011"; 
  google_ad_width = 300; 
  google_ad_height = 250;
  document.getElementById("sda-post-bottom-right").style.cssFloat = "right";
  document.getElementById("sda-post-bottom-right").style.margin = "10px 0 15px 15px";
} 
else { 
  google_ad_slot = "8907234011"; 
  google_ad_width = 300; 
  google_ad_height = 250;
  document.getElementById("sda-post-bottom-right").style.textAlign = "center";
  document.getElementById("sda-post-bottom-right").style.margin = "5px 0 5px 0";
}
</script>
<!-- Ezoden - Post Bottom -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';
*/

/*$adsPostBottom = '<div style="margin: 10px 0 0 0;text-align:center;"><script type="text/javascript">
	var width = window.innerWidth 
        || document.documentElement.clientWidth 
        || document.body.clientWidth;
    google_ad_client = "ca-pub-1581664623738680";
	    if (width >= 1160) {
	        google_ad_slot = "5558435413"; 
	        google_ad_width = 336; 
	        google_ad_height = 280;
	    } else if (width >= 480) { 
	        google_ad_slot = "5558435413"; 
	        google_ad_width = 336; 
	        google_ad_height = 280;
	    } else { 
	        google_ad_slot = "6191947819"; 
	        google_ad_width = 320; 
	        google_ad_height = 100;
	    }
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';*/

/*$adsPostBottom = '<div style="margin: 10px 0 0 0;text-align:center;"><script type="text/javascript">
	var width = window.innerWidth 
        || document.documentElement.clientWidth 
        || document.body.clientWidth;
    google_ad_client = "ca-pub-1581664623738680";
    if (width >= 1160) {
        google_ad_slot = "5558435413"; 
        google_ad_width = 336; 
        google_ad_height = 280;
    } else { 
        google_ad_slot = "8907234011"; 
        google_ad_width = 300; 
        google_ad_height = 250;
    }
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';*/

#$testAdLeft300x250='<div style="float:left;width:300px;height:250px;margin:0 10px 10px 0;background-color:grey;"></div>';

// if($nbOfPTags >= 6 || ($nbOfPTags > 3 && count(explode('<img', $content)) >= 2)) {
// 	$html .= $testAd300x250; //$adsPostBottom;
// }

// Ads
/*$adsPostTop = '<div style="margin:0 0 0 -6px;"><script type="text/javascript">
	var width = window.innerWidth 
        || document.documentElement.clientWidth 
        || document.body.clientWidth;
    google_ad_client = "ca-pub-1581664623738680";
    if (width >= 1160) {
        google_ad_slot = "1239069700"; 
        google_ad_width = 700; 
        google_ad_height = 60;
    } else if (width >= 480) { 
        google_ad_slot = "1239069700"; 
        google_ad_width = 468; 
        google_ad_height = 60;
    } else { 
        google_ad_slot = "4715214619"; 
        google_ad_width = 320; 
        google_ad_height = 100;
    }
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';*/

/*$adsPostMiddle = '<script type="text/javascript">
	var width = window.innerWidth 
        || document.documentElement.clientWidth 
        || document.body.clientWidth;
    google_ad_client = "ca-pub-1581664623738680";
    if (width >= 1160) {
        google_ad_slot = "2792686216"; 
        google_ad_width = 728; 
        google_ad_height = 90;
    } else if (width >= 480) { 
        google_ad_slot = "7668681016"; 
        google_ad_width = 468; 
        google_ad_height = 60;
    } else { 
        google_ad_slot = "6191947819"; 
        google_ad_width = 320; 
        google_ad_height = 100;
    }
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';*/


/* ADS ASYNC
$rand = rand(1, 2);

// Ads
$adsPostTop = '<style>
				.ezoden-post-top-adp { width: 310px; height: 60px; }
				@media(min-width: 480px) { .ezoden-post-top-adp { width: 468px; height: 60px; } }';
if($rand == 3) {
	$adsPostTop .= '@media(min-width: 1160px) { .ezoden-post-top-adp { float: left; width: 305px; height: 255px; margin: 0 10px 10px -10px; } }';
}				
else {
	$adsPostTop .= '@media(min-width: 1160px) { .ezoden-post-top-adp { width: 728px; height: 90px; margin: 0 0 10px -10px; } }';
}
$adsPostTop .= '</style>
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Ezoden - Post Top Adp -->
				<ins class="adsbygoogle ezoden-post-top-adp"
				     style="display:block"
				     data-ad-client="ca-pub-1581664623738680"
				     data-ad-slot="7094265015"
				     data-ad-format="auto"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
				';

$adsPostMiddle = '<style>
					.ezoden-post-middle-adp { width: 310px; height: 60px; }
					@media(min-width: 480px) { .ezoden-post-middle-adp { width: 468px; height: 60px; } }
					@media(min-width: 1160px) { .ezoden-post-middle-adp { width: 728px; height: 90px; margin: 0 0 0 -10px; } }
					</style>
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Ezoden - Post Middle Adp -->
					<ins class="adsbygoogle ezoden-post-middle-adp"
					     style="display:block"
					     data-ad-client="ca-pub-1581664623738680"
					     data-ad-slot="4699201818"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>';

$adsPostBottom = '<style>
					.ezoden-post-bottom-adp { width: 300px; height: 250px; margin: 0 auto 0 auto; }
					@media(min-width: 480px) { .ezoden-post-bottom-adp { width: 300px; height: 250px; margin: 0 auto 0 auto; } }';
if($rand != 2) {
	$adsPostBottom .= '@media(min-width: 1160px) { .ezoden-post-bottom-adp { width: 728px; height: 90px; margin: 0 0 10px -10px; } }';
}				
else {
	$adsPostBottom .= '@media(min-width: 1160px) { .ezoden-post-bottom-adp { width: 336px; height: 280px; margin: 0 auto 0 auto; } }';
}
$adsPostBottom .= '</style>
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Ezoden - Post Bottom Adp -->
					<ins class="adsbygoogle ezoden-post-bottom-adp"
					     style="display:block"
					     data-ad-client="ca-pub-1581664623738680"
					     data-ad-slot="3156273015"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>';
*/


/**
 * OLD WAY TO DO SUMMARY (eg. 1-1-1 // 2-1 // etc...)
 */
/***************
if(isset($data['summary']) && $data['summary'] != '') {
	$html .= '<div>Summary</div><ul>';
	$prevPage = array();
	foreach ($data['summary'] as $key => $value) {
		$p = explode('-', $value['pagination']);

		foreach ($prevPage as $k => $v) {
			if($k >= 1 && !isset($p[$k])) {
				$html .= '</ul>';
			}
		}

		if((isset($p[1]) && $p[1] == 1) || (count($p) - 1 > 1 && isset($p[count($p) - 1]) && $p[count($p) - 1] == 1)) {
			$html .= '<ul>';
		}

		$html .= '<li><a href="/'.$data['category'].'/'.$value['id'].'/'.$value['slug'].'">'.$value['title'].'</a></li>';

		$prevPage = $p;
	}
	$html .= '</ul>';
}
****************/

/**
 * AN OTHER WAY TO DO SUMMARY WITH function buildTree()
 */
/***************
function generateSumary($data, $postId, $html='', $currLevel = 0, $prevLevel = -1) {
	foreach ($data as $key => $value) {

		if ($currLevel > $prevLevel) {
	        $html .= "<ul>"; 
	    }

	    if ($currLevel == $prevLevel) {
	        $html .= "</li>";
	    }

		if(isset($value['title'])) {
			$html .= '<li>'.$value['title'];
		}

		if ($currLevel > $prevLevel) { 
	        $prevLevel = $currLevel; 
	    }

		$currLevel++; 

		if(isset($value['children']) && is_array($value['children'])) {
			$html = generateSumary($value['children'], $postId, $html, $currLevel, $prevLevel);		
		}

		$currLevel--;
	
	}
	if ($currLevel == $prevLevel) {
		$html .= "</li></ul>";
	}
	return $html;
}
***************/


/* OLD
function buildSumary($data, $postId, $summary='', $currentParent=0, $currLevel = 0, $prevLevel = -1) {
	foreach ($data['summary'] as $id => $v) {
		if ($currentParent == $v['parentId']) {
			if ($currLevel > $prevLevel) {
				$summary .= "<ul>"; 
			}

			if ($currLevel == $prevLevel) {
				$summary .= "</li>";
			}

			$cssSelected = ($id == $postId) ? 'class="summary-selected"' : '';
			$summary .= '<li><a '.$cssSelected.' href="/'.$data['name'].'/'.$id.'/'.$v['slug'].'">'.$v['title'].'</a>';

			if ($currLevel > $prevLevel) { 
				$prevLevel = $currLevel; 
			}

			$currLevel++; 
			$summary = buildSumary($data, $postId, $summary, $id, $currLevel, $prevLevel);
			$currLevel--;
		}   
	}

	if ($currLevel == $prevLevel) {
		$summary .= "</li></ul>";
	}

	return $summary;
}
*/


/*	Ads in function of post lengh	
$pTags = count(explode('</p>', $content));
if($pTags >= 11 || ($pTags > 6 && count(explode('<img', $content)) >= 2)) {
	$html .= '<div>
				<style>
				.ezoden-post-top-adp { width: 320px; height: 50px; }
				@media(min-width: 468px) { .ezoden-post-top-adp { width: 468px; height: 60px; } }
				@media(min-width: 728px) { .ezoden-post-top-adp { width: 728px; height: 90px; margin: 0 0 0 -10px; } }
				</style>
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Ezoden - Post Top Adp -->
				<ins class="adsbygoogle ezoden-post-top-adp"
				     style="display:inline-block"
				     data-ad-client="ca-pub-1581664623738680"
				     data-ad-slot="5617531818"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>';
}
*/