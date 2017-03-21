<?php

/*
 * Get data for index view
 * Get data for single media view
 * Call View
 */

namespace Applications\Backend\Modules\Medias;

class MediasControler extends \Library\Controler {
	protected $MediasData;
	protected $OptionsData;

	public function __construct($App) {
		parent::__construct($App);
		$tablePrefix = \Library\Config::getSetting('tablePrefix');
		$this->MediasData = new \Library\Models\MediasManager($tablePrefix); 
		$this->OptionsData = new \Library\Models\OptionsManager($tablePrefix);
	}

	public function index() {
		$nbOfMedias = $this->MediasData->getNbOfAllMedias()->fetchColumn();
		//$this->vars['nbOfPostsPerPage'] = $this->PostsData->nbOfPostsPerPage()->fetch()['value'];
		$this->vars['nbOfMediasPerPage'] = 100;

		if(isset($this->vars['page']) && $this->vars['page'] > 1 && $this->vars['page'] <= ceil($nbOfPosts/$this->vars['nbOfMediasPerPage'])) {
			$this->vars['startMedia'] = ($this->vars['nbOfMediasPerPage']*($this->vars['page']-1));
		}
		else {
			$this->vars['startMedia'] = 0;
		}

		$medias['data'] = $this->MediasData->getAllMediasInRange($this->vars['startMedia'], $this->vars['nbOfMediasPerPage'])->fetchAll();

		$medias['nbOfMediasPerPage'] =  $this->vars['nbOfMediasPerPage'];
		$medias['nbOfMedias'] =  $nbOfMedias;

		$this->generateView($medias);
	}

	public function single() { 
		if(isset($this->vars['id'])) {
			$media = $this->MediasData->getMedia($this->vars['id'])->fetch();

			$media['template'] = $this->configData['template'] = $this->OptionsData->option('template')->fetch()['value'];
		}
		else {
			$media = array('new' => true);
		}

		$this->generateView($media);
	}

	public function action() { 
		echo'<pre>';print_r($_FILES);echo'</pre>';
		$path = WEB_PATH.MEDIAS_DIRECTORY.$_POST['filePath'];
		if (!file_exists($path)) {
		    mkdir($path, 0755, true);
		}	

		$resultat = move_uploaded_file($_FILES['media']['tmp_name'],$path.$_POST['fileName']);	

		exit;

		if($this->vars['RequestMethod'] == 'POST') {
			if(isset($_POST['newPost']) && !empty($_POST['newPost']) && isset($_POST['content']) && !empty($_POST['content']) && isset($_POST['title']) && !empty($_POST['title'])) {
				$title = ucwords(mb_strtolower($_POST['title']));
				$content = $this->cleanPost($_POST['content']);
				$this->PostsData->newPost($title, $content, $_POST['slug'], $_POST['imageId'], $_POST['source'], $_POST['sourceUrl'], 1);
			}

			if(isset($_POST['updatePostId']) && !empty($_POST['updatePostId']) && isset($_POST['content']) && !empty($_POST['content']) && isset($_POST['title']) && !empty($_POST['title'])) {
				$title = ucwords(mb_strtolower($_POST['title']));
				$content = $this->cleanPost($_POST['content']);
				$this->PostsData->updatePost($this->vars['id'], $title, $_POST['slug'], $_POST['imageId'], $_POST['source'], $_POST['sourceUrl'], $content);
			}
		}
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