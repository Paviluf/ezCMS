<?php

/*
 * Get data for index view
 * Get data for single post view
 * Call View
 */

namespace Applications\Backend\Modules\Posts;

class PostsControler extends \Library\Controler {
	protected $PostsData;
	protected $OptionsData;

	public function __construct($App) {
		parent::__construct($App);
		$tablePrefix = \Library\Config::getSetting('tablePrefix');
		$this->PostsData = new \Library\Models\PostsManager($tablePrefix); 
		$this->OptionsData = new \Library\Models\OptionsManager($tablePrefix);
	}

	public function cleanPost($str) {
		return trim(preg_replace('/\s\s+/', ' ', preg_replace("/&nbsp;/u", " ", $str)));
	}

	public function index() {
		$nbOfPosts = $this->PostsData->nbOfAllPosts($this->vars)->fetchColumn();
		$this->vars['nbOfPostsPerPage'] = 100;

		if(isset($this->vars['page']) && $this->vars['page'] > 1 && $this->vars['page'] <= ceil($nbOfPosts/$this->vars['nbOfPostsPerPage'])) {
			$this->vars['startPost'] = ($this->vars['nbOfPostsPerPage']*($this->vars['page']-1));
		}
		else {
			$this->vars['startPost'] = 0;
		}

		$posts['data'] = $this->PostsData->allPosts($this->vars)->fetchAll();

		$posts['nbOfPostsPerPage'] =  $this->vars['nbOfPostsPerPage'];
		$posts['nbOfPosts'] =  $nbOfPosts;

		$this->generateView($posts);
	}

	public function single() {
		$post = array();
		$post['template'] = $this->OptionsData->option('template')->fetch()['value'];

		if(isset($this->vars['modType']) && $this->vars['modType'] == 'add') {	
			$post['modType'] = $this->vars['modType'];
			if($this->vars['RequestMethod'] == 'POST') {
				if(isset($_POST['newPost']) && !empty($_POST['newPost']) && isset($_POST['content']) && !empty($_POST['content']) && isset($_POST['title']) && !empty($_POST['title'])) {
					$this->add();
					header('Location: /admin/post/update/'.$id.'');
					exit;
				}
			}
		}
		else if(isset($this->vars['modType']) && $this->vars['modType'] == 'update') {
			$post['modType'] = $this->vars['modType'];
			if($this->vars['RequestMethod'] == 'POST') {
				if(isset($_POST['updatePostId']) && !empty($_POST['updatePostId']) && isset($_POST['content']) && !empty($_POST['content']) && isset($_POST['title']) && !empty($_POST['title'])) {
					$this->update();
				}
			}

			$post = $this->PostsData->post($this->vars['id'])->fetch();

			//$post['author'] = ucfirst(mb_strtolower($this->PostsData->postAuthor($post['userId_FK'])->fetch()['name']));
		}
		else if(isset($this->vars['modType']) && $this->vars['modType'] == 'delete') {
			$post['modType'] = $this->vars['modType'];
			
		}
		else {
			// exception
		}

		$this->generateView($post);
	}

	
	public function add($data) { 
		$title = ucwords(mb_strtolower($_POST['title']));
		$content = $this->cleanPost($_POST['content']);
		$id = $this->PostsData->add($title, $content, $_POST['slug'], $_POST['imageId'], $_POST['source'], $_POST['sourceUrl'], 1);	
	}

	public function update($data) { 
		$title = ucwords(mb_strtolower($_POST['title']));
		$content = $this->cleanPost($_POST['content']);
		$this->PostsData->update($_POST['updatePostId'], $title, $_POST['slug'], $_POST['imageId'], $_POST['source'], $_POST['sourceUrl'], $content);
	}

	public function delete($postId) { 
		$this->PostsData->delete($postId);
	}		
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