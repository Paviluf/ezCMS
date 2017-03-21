<?php

/**
 * Functions
 */

namespace Library;

abstract class Functions {
	// Generate index title 
	public static function generateIndexTitle($str) {
		return '<h1 class="index-title">'.ucwords(mb_strtolower($str)).'</h1>';
	}

	// Generate single title 
	public static function generateSingleTitle($str) {
		return '<h1 class="content-title">'.mb_strtoupper($str).'</h1>';
	}

	// Generate single title (parent title + title)
	public static function generateSingleParentTitle($str, $strTwo) {
		return '<h1 class="content-title">'.mb_strtoupper($str).'</h1>
		 <h2 class="content-child-title">'.mb_strtoupper($strTwo).'</h2>';
	}

	// Detect if client is a Bot
	public static function botDetect() {
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			// Bots list
			$botsList=array(
			"Google"=>"Googlebot",
			"Yahoo"=>"Slurp",
			"Bing"=>"bingbot");

			$regexp='/'.implode("|", $botsList).'/';
			if(preg_match($regexp, $_SERVER['HTTP_USER_AGENT'], $matches)) {
				//$bot = array_search($matches[0], $botsList);
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	// Strip HTML Tags and Cut the string at specified value. Add ellipsis if needed
	public static function htmlToStr($str, $lengh, $quotes=false, $ellipsis=false) {
		// Strip HTML Tags and convert <p>, etc... to simple space
		$str = str_replace(array('</p>', '<br>', '<br/>', '<br />'), ' ', $str);
		$str = strip_tags($str);

		if($quotes == 'simple') {
			$str = str_replace("'", '', $str);
		}
		else if($quotes == 'double') {
			$str = str_replace('"', '', $str);
		}
		else if($quotes == 'both') {
			$str = str_replace("'", '', $str);
			$str = str_replace('"', '', $str);
		}

		$str = preg_replace('/\s\s+/', ' ', $str); 
		$str = trim($str);

		// Cut at specified value
		if(mb_strlen($str) > $lengh) {
			$str = mb_substr($str, 0 , $lengh);
			$str = mb_substr($str, 0, mb_strrpos($str, ' '));
			// Add ellipsis if needed
			if($ellipsis) {
				$str .= '...';
			}
		}
		return $str;
	}

	// Convert to slug (url complient)
	public static function slugify($text) { 
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

	// Create pagination for index view
	/*
		IN
		--
		nbOfPostsPerPage
		nbOfPosts
		page
		category
		---
		OUT
		---
		pagePrevHtml
		pageFirstHtml
		pagination
		pageLastHtml
		$pageNextHtml
	*/
	public static function paginationCategory($nbOfPostsPerPage, $nbOfPosts, $page, $category) {
		$nbOfPagesBeforeAndAfter = 5;
		$nbOfPages = ceil($nbOfPosts / $nbOfPostsPerPage);

		if($nbOfPages > 1) {
			if(!isset($page)) {
				$page = 1;
			}

			if($page - $nbOfPagesBeforeAndAfter > 1 && $page + $nbOfPagesBeforeAndAfter <= $nbOfPages) {
				$pageFirst = $page - $nbOfPagesBeforeAndAfter;
			}
			else if ($page + $nbOfPagesBeforeAndAfter >= $nbOfPages) {
				if($nbOfPages - 8 < 1) {
					$pageFirst = 1;
				}
				else {
					$pageFirst = $nbOfPages - 8;
				}
			}
			else {
				$pageFirst = 1;
			}

			if($page + $nbOfPagesBeforeAndAfter >= 9 && $nbOfPages >= 9 && $page + $nbOfPagesBeforeAndAfter <= $nbOfPages) {
				$pageLast = $page + $nbOfPagesBeforeAndAfter;
			}
			else if ($page + $nbOfPagesBeforeAndAfter >= $nbOfPages || $nbOfPages <= 9) {
				$pageLast = $nbOfPages;
			}
			else {
				$pageLast = 9;
			}

			$pagination = '';
			for($i=$pageFirst;$i<=$pageLast;$i++) {
				$pagination .='<div class="pagination-page-nb"><a href="/';

				if($i == 1) {
					$pagination .= '">';
				}
				else {
					$pagination .= $category.'/'.$i.'">';
				}
				
				if($i == $page) {
					$pagination .= '<span class="pagination-page-selected">'.$i.'</span>';
				}
				else {
					$pagination .= $i;
				}
				$pagination .='</a></div>';
			}

			$pagePrev = '';
			$pagePrevHtml = '';
			if($page - 1 >= 1) {
				$pagePrevHtml = '<a href="/';

				$pagePrev = $page - 1;

				if($pagePrev != 1 || ($pagePrev == 1 && $category != 'news')) {
					$pagePrevHtml .= $category.'/';
				}

				if($pagePrev == 1) {
					$pagePrev = '';
				}
				
				$pagePrevHtml .= $pagePrev.'">Prev.</a>';
			}

			$pageNext = '';
			$pageNextHtml = '';
			if($page + 1 <= $pageLast) {
				$pageNext = $page + 1;
				$pageNextHtml = '<a href="/'.$category.'/'.$pageNext.'">Next</a>';
			}

			$pageFirstHtml = '';
			if($pageFirst > 1) {
				$pageFirstHtml = ' <a href="/';
				if($category != 'news') {
					$pageFirstHtml .= $category.'/';
				}
				$pageFirstHtml .= '">'.htmlspecialchars('<<').'</a> ';
			}

			$pageLastHtml = '';
			if($pageLast < $nbOfPages) {
				$pageLastHtml = ' <a href="/'.$category.'/'.$nbOfPages.'">'.htmlspecialchars('>>').'</a> ';
			}

			$html = '<div class="pagination">
						<div class="pagination-page-prev">
							'.$pagePrevHtml.'
						</div>	
						<div class="pagination-pages">
							<div class="pagination-pages-wrap">
								<div class="pagination-page-nb">
								'.$pageFirstHtml.'
								</div>
								'.$pagination.'			
								<div class="pagination-page-nb">
								'.$pageLastHtml.'
								</div>
							</div>
						</div>
						<div class="pagination-page-next">
							'.$pageNextHtml.'
						</div>
					</div>';
			return $html;
		}
	}

	// Build Sumary for multipages post
	public static function buildSumary($summaryData, $postId, $name) {
		$right = array();  
		$prevLevel = -1;
		$summary = '<div class="box summary">
						<h1>Summary</h1>';
		$close = '';
		foreach ($summaryData as $key => $value) {
       		// only check stack if there is one  
			if (count($right)>0) {  
            // check if we should remove a node from the stack  
				while ($right[count($right)-1]<$value['rgt']) {  
					array_pop($right);  
				}  
			}  

			$currLevel = count($right);

			if ($currLevel > $prevLevel) {
				$summary .= "<ul>"; 
			}	

			$delta = $prevLevel - $currLevel;
			while($delta > 0) {
				if ($currLevel <= $prevLevel) {
					$summary .= "</li>";
				}	

				if ($currLevel < $prevLevel) {
					$summary .= "</ul>";
				}
				--$delta;
			}
			
			$cssSelected = ($value['id_PK'] == $postId) ? 'class="summary-selected"' : '';
			$summary .= '<li><a '.$cssSelected.' href="/'.$name.'/'.$value['id_PK'].'/'.$value['slug'].'">'.$value['title'].'</a>';

			$prevLevel = count($right);

       		// add this node to the stack  
			$right[] = $value['rgt'];  	
		} 

		while(count($right)>0) {
			$summary .= "</li></ul>";
			array_pop($right);  
		}

		return $summary.'</div>';
	}

	// Build Menu
	public static function buildMenu($menu, $category, $host) {
		$html = '';
		foreach ($menu as $k => $element) {
			$cssMenuSelected = '';
			$link = '';
			if($element['name'] == $category) {
				$cssMenuSelected = 'class="menuSelected"';
			}

			// Check if $menuUrl is a full link
			$link = mb_strrchr($element['url'] , 'http') ? $element['url'] : $host.$element['url'];

			$html .= '<li '.$cssMenuSelected.'><a href="'.$link.'">'.mb_strtoupper($element['label']).'</a></li>';
		}
		return $html;
	}

	

}



/*-
	// Build Sumary for multipages post
	public static function buildSumary($summaryArray, $postId, $name, $summary='', $currentParent=0, $currLevel = 0, $prevLevel = -1) {
		foreach ($summaryArray as $id => $v) {
			if ($currentParent == $v['parentId']) {
				if ($currLevel > $prevLevel) {
					$summary .= "<ul>"; 
				}

				if ($currLevel == $prevLevel) {
					$summary .= "</li>";
				}

				$cssSelected = ($id == $postId) ? 'class="summary-selected"' : '';
				$summary .= '<li><a '.$cssSelected.' href="/'.$name.'/'.$id.'/'.$v['slug'].'">'.$v['title'].'</a>';

				if ($currLevel > $prevLevel) { 
					$prevLevel = $currLevel; 
				}

				$currLevel++; 
				$summary = Self::buildSumary($summaryArray, $postId, $name, $summary, $id, $currLevel, $prevLevel);
				$currLevel--;
			}   
		}

		if ($currLevel == $prevLevel) {
			$summary .= "</li></ul>";
		}

		return $summary;
	}

	*/