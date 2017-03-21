<?php

/*
 * Get data for index view
 * Get data for single post view
 * Call View
 */

namespace Applications\Frontend\Modules\Posts;

class PostsControler extends \Library\Controler {
	protected $PostsData;
	protected $OptionsData;
	protected $nbOfPostsPerPage;
	protected $siteName;

	public function __construct($App) {
		parent::__construct($App);
		$tablePrefix = \Library\Config::getSetting('tablePrefix');
		$this->PostsData = new \Library\Models\PostsManager($tablePrefix);
		$this->OptionsData = new \Library\Models\OptionsManager($tablePrefix);
		$this->nbOfPostsPerPage = '';
		$this->siteName = \Library\Config::getSetting('siteName');
	}

	public function index() {
		try {
			// Check if the category requested exist
			if(!$this->PostsData->categoryExist($this->vars['category'])->fetch()) {
				throw new \Library\Error404("This category doesn't exist");
			}

			// Get the number of posts for the category
			$nbOfPosts = $this->PostsData->nbOfPostsForCategory($this->vars['category'])->fetchColumn();

			// Get / Set the number of posts per page
			if(empty($this->vars['nbOfPostsPerPage']) && isset($_SESSION['nbOfPostsPerPage']) && !empty($_SESSION['nbOfPostsPerPage'])) {
				$this->vars['nbOfPostsPerPage'] = $_SESSION['nbOfPostsPerPage'];
			}
			else {
				$_SESSION['nbOfPostsPerPage'] = $this->vars['nbOfPostsPerPage'] = $this->OptionsData->option('nbOfPostsPerPage')->fetch()['value'];
			}

			// Get first post
			if(isset($this->vars['page'])) {
				$this->vars['page'] = intval($this->vars['page']);
				if($this->vars['page'] > 1 && $this->vars['page'] <= ceil($nbOfPosts/$this->vars['nbOfPostsPerPage'])) {
					$this->vars['startPost'] = $this->vars['nbOfPostsPerPage']*($this->vars['page']-1);
				}
				else {
					throw new \Library\Error404("This page doesn't exist");
				}
			}
			else if (!isset($this->vars['page'])) {
				$this->vars['startPost'] = 0;
			}
			else {
				throw new \Library\Error404("This page doesn't exist");
			}

			// Get posts data
			$posts = array();
			$posts['data'] = $this->PostsData->posts($this->vars['category'], $this->vars['startPost'], $this->vars['nbOfPostsPerPage'])->fetchAll();

			// Build imagesSqlWhere and Set posts Data - date
			$imagesSqlWhere = '';
			foreach ($posts['data'] as $k => $v) {
				$posts['data'][$k]['excerpt'] = \Library\Functions::htmlToStr($v['content'], 180, false, true);
				unset($posts['data'][$k]['content']);
				if(isset($v['imageId']) && !empty($v['imageId'])) {
					$imagesSqlWhere .= 'id_PK='.$v['imageId'].' OR ';
				}
				if(isset($v['dateModified']) && $v['dateModified'] > $v['dateCreated']) {
					$posts['data'][$k]['date'] = $v['dateModified'];
				}
				else {
					$posts['data'][$k]['date'] = $v['dateCreated'];
				}
				$posts['data'][$k]['date'] = date('m/d/Y g:ia', strtotime($posts['data'][$k]['date']));
			}

			if(!empty($imagesSqlWhere)) {
				// Delete last ' OR '
				$imagesSqlWhere = substr($imagesSqlWhere, 0, -4);
				// Get images infos
				$imageData = $this->PostsData->imageData($imagesSqlWhere)->fetchAll();

				// Get posts Data - imagePath and imageName
				if(!empty($imageData)) {
					foreach ($posts['data'] as $k => $v) {
						if(isset($v['imageId']) && !empty($v['imageId'])) {
							foreach ($imageData as $vImage) {
								if($v['imageId'] == $vImage['id_PK']) {
									$posts['data'][$k]['imagePath'] = $vImage['filePath'];
									$posts['data'][$k]['imageName'] = $vImage['fileName'];
								}
							}
						}
					}
				}
			}

			// Get more posts Data
			$posts['nbOfPostsPerPage'] =  $this->vars['nbOfPostsPerPage'];
			$posts['nbOfPosts'] =  $nbOfPosts;
			$posts['category'] = $this->vars['category'];
			$posts['index'] = TRUE;

			if(isset($this->vars['home']) && $this->vars['home']) {
				$posts['home'] = $this->vars['home'];
			}

			$posts['pageTitle'] = '';
			if(isset($this->vars['homeTitle']) && !empty($this->vars['homeTitle'])) {
				$posts['pageTitle'] = $this->vars['homeTitle'].' - ';
			}
			$posts['pageTitle'] .= ucfirst($posts['category']);

			if(isset($this->vars['homeDescription']) && !empty($this->vars['homeDescription'])) {
				$posts['pageDescription'] = $this->vars['homeDescription'].' - '.$this->siteName;
			}
			else {
				$posts['pageDescription'] = $posts['pageTitle'].' - '.$this->siteName;
			}

			if(isset($this->vars['page']) && !empty($this->vars['page'])) {
				$posts['pageTitle'] .= ' - Page '.$this->vars['page'];
				$posts['pageDescription'] = $posts['pageTitle'].' - '.$this->siteName;
				$posts['page'] = $this->vars['page'];
			}
			else {
				$posts['page'] = 1;
			}

			// Call view
			$this->generateView($posts);
		}
		catch (\Library\Error404 $e) {
			$e->getMsg();
		}
	}

	public function single() {
		try {
			// Get post data
			$this->vars['id'] = intval($this->vars['id']);
			$post = $this->PostsData->post($this->vars['id'])->fetch();
			if(empty($post)) {
				throw new \Library\Error404("This post doesn't exist");
			}

			// Check post URI (slug and name)
			$uriExploded = explode('/', $_SERVER['REQUEST_URI']);
			foreach ($uriExploded as $key => $value) {
				$uriExploded[$key] = urldecode($value);
			}
			if($uriExploded[count($uriExploded)-1] != $post['slug']) {
				throw new \Library\Error404("This post doesn't exist");
			}
			if(isset($post['name']) && !empty($post['name']) && $uriExploded[1] != $post['name']) {
				throw new \Library\Error404("This post doesn't exist");
			}

			// Get post category name
			if(isset($post['name']) && !empty($post['name'])) {
				$post['category'] = $this->PostsData->categoryPostsMultiPages($post['postMultiPagesId_FK'])->fetch()['name'];
			}
			else {
				$post['category'] = $this->PostsData->category($post['id_PK'])->fetch()['name'];
			}

			$post['parentTitle'] = $this->PostsData->parentTitle($post['postMultiPagesId_FK'], $post['lft'], $post['rgt'])->fetch()['parentTitle'];

			// Get post author name
			$post['author'] = ucfirst(mb_strtolower($this->PostsData->postAuthor($post['userId_FK'])->fetch()['name']));

			// Get post date
			if(isset($post['dateModified']) && $post['dateModified'] > $post['dateCreated']) {
				$post['date'] = $post['dateModified'];
			}
			else {
				$post['date'] = $post['dateCreated'];
			}

			// Build imagesSqlWhere from content and post imageId field
			$imagesSqlWhere = '';
			if(!empty($post['imageId'])) {
				$imagesSqlWhere .= 'id_PK='.$post['imageId'].' OR ';
			}
			// For UTF-8 preg_match('/regex/u') - note the /u
			if(preg_match("/ez_image-[0-9]+/u", $post['content'], $matches)) {
				foreach($matches as $k => $v) {
					// if needed - delete ez_image-[0-9]
					//$post['content'] = str_replace($v, '', $post['content']);
					$match = explode('-', $v);
					$imagesSqlWhere .= 'id_PK='.$match[count($match)-1].' OR ';
				}
			}
			if(!empty($imagesSqlWhere)) {
				// Delete last ' OR '
				$imagesSqlWhere = substr($imagesSqlWhere, 0, -4);
				// Get images infos
				$imageData = $this->PostsData->imageData($imagesSqlWhere)->fetchAll();
				// Get imageSource
				if(!empty($imageData)) {
					$post['imageSource'] = '';
					foreach ($imageData as $k => $v) {
						if(!empty($v['sourceUrl']) && !empty($v['source'])) {
							$post['imageSource'] .= '<a href="'.$v['sourceUrl'].'" target="_blank" >'.$v['source'].'</a>, ';
						}
						else if(!empty($v['source'])) {
							$post['imageSource'] .= $v['source'].', ';
						}
					}
					$post['imageSource'] = mb_substr($post['imageSource'], 0, -2);
				}
			}

			if(isset($post['name']) && !empty($post['name'])) {
				// Get summary
				$summary = $this->PostsData->summary($post['id_PK']);

				if(!empty($summary) && $summary->rowCount() >= 2) {
					$post['summary'] = array();
					while($summaryData = $summary->fetch()) {
						$post['summary'][$summaryData['id_PK']] = array('id_PK' => $summaryData['id_PK'], "lft" => $summaryData['lft'], "rgt" => $summaryData['rgt'], "title" => $summaryData['title'], 'slug' => $summaryData['slug']);
					}

					// Sort summary pages
					$pagesInOrder = $post['summary']; //$this->sortPages($post['summary']);

					// Get previous and next post
					foreach ($pagesInOrder as $k => $v) {
						if($v['id_PK'] == $post['id_PK']) {
							if(isset($pagesInOrder[$k-1]['id_PK'])) {
								$post['prevPost'] = $pagesInOrder[$k-1];
							}
							if(isset($pagesInOrder[$k+1]['id_PK'])) {
								$post['nextPost'] = $pagesInOrder[$k+1];
							}
						}
					}
				}
			}
			else {
				// Get previous and next post
				$post['prevPost'] =  $this->PostsData->prevPost($post['category'], $post['id_PK'], $post['date'])->fetch();
				if(isset($post['prevPost']['title']) && !empty($post['prevPost']['title'])) {
					$post['prevPost']['title'] = ucfirst(strtolower($post['prevPost']['title']));
				}

				$post['nextPost'] =  $this->PostsData->nextPost($post['category'], $post['id_PK'], $post['date'])->fetch();
				if(isset($post['nextPost']['title']) && !empty($post['nextPost']['title'])) {
					$post['nextPost']['title'] = ucfirst(strtolower($post['nextPost']['title']));
				}
			}

			// Add +1 to post viewed
			$this->PostsData->incrementView($post['id_PK']);

			// Get more posts Data
			if(empty($post['pageTitle'])) {
				if(isset($post['parentTitle']) && !empty($post['parentTitle'])) {
					$post['pageTitle'] = $post['parentTitle'].' : '.$post['title'];
				}
				else {
					$post['pageTitle'] = $post['title'];
				}
			}

			if(empty($post['pageDescription'])) {
				$post['pageDescription'] = \Library\Functions::htmlToStr($post['content'], 180, 'double', true);
			}

			// Call View
			$this->generateView($post);
		}
		catch (\Library\Error404 $e) {
			$e->getMsg();
		}
	}

	// private function sortPages($data, $summary=array(), $currentParent=0, $currLevel = 0, $prevLevel = -1) {
	// 	foreach ($data as $id => $value) {
	// 		if ($currentParent == $value['parentId']) {
	// 			$summary[] = array('id_PK' => $id, 'parentId' => $value['parentId'], 'title' => $value['title'], 'slug' => $value['slug']);

	// 			if ($currLevel > $prevLevel) {
	// 				$prevLevel = $currLevel;
	// 			}

	// 			$currLevel++;
	// 			$summary = $this->sortPages($data, $summary, $id, $currLevel, $prevLevel);
	// 			$currLevel--;
	// 		}
	// 	}
	// 	return $summary;
	// }
}


/**
 * AN OTHER WAY TO DO SUMMARY WITH function createTreeView()
 */
/***************
public function createTreeView($data, $postId, $currentParent=0, $currLevel = 0, $prevLevel = -1, $summaryHmtlAndPagesInOrder=array('html' => '', 'pages' => '')) {

    foreach ($data as $id => $value) {

      if ($currentParent == $value['parentId']) {
          if ($currLevel > $prevLevel) {
            $summaryHmtlAndPagesInOrder['html'] .= "<ul>";
          }

          if ($currLevel == $prevLevel) {
            $summaryHmtlAndPagesInOrder['html'] .= "</li>";
          }

          $css = ($id == $postId) ? 'style="color:red;"' : '';

          $summaryHmtlAndPagesInOrder['html'] .= '<li><span '.$css.'>'.$value['title'].'</span>';
          $summaryHmtlAndPagesInOrder['pages'][] = array('id' => $id, 'title' => $value['title'], 'slug' => $value['slug']);

          if ($currLevel > $prevLevel) {
            $prevLevel = $currLevel;
          }

          $currLevel++;

          $summaryHmtlAndPagesInOrder = $this->createTreeView ($data, $postId, $id, $currLevel, $prevLevel, $summaryHmtlAndPagesInOrder);

          $currLevel--;
      }
    }

    if ($currLevel == $prevLevel) {
      $summaryHmtlAndPagesInOrder['html'] .= "</li></ul>";
    }
    return $summaryHmtlAndPagesInOrder;
    }
}
***************/
