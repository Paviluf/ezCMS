<?php

/*
 * Get Posts data
 */

namespace Library\Models;

class PostsManager extends \Library\Model {
	public function __construct($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
		$this->table = $this->tablePrefix.'posts';
	}

	public function nbOfAllPosts() {
		$sql = "SELECT COUNT(*) FROM ".$this->table;
		return $this->execSQL($sql);
	}

	public function nbOfAllActivePosts() {
		$sql = "SELECT COUNT(*) FROM ".$this->table."
		WHERE status = ".ACTIVE;
		return $this->execSQL($sql);
	}

	public function nbOfPostsForCategory($category) {
		$sql = "SELECT COUNT(*) FROM ".$this->table." AS posts
			LEFT JOIN ".$this->tablePrefix."categories AS categories ON categories.name = :category AND categories.status = ".ACTIVE."
			LEFT JOIN ".$this->tablePrefix."postsCategories AS postsR ON postsR.categoryId_FK = categories.id_PK
			WHERE posts.id_PK = postsR.postId_FK AND posts.status = ".ACTIVE;
		$params = array();
		$params[] = array('param' => ':category', 'value' => $category, 'type' => \PDO::PARAM_STR);		
		return $this->execSQL($sql, $params);
	}

	public function allPosts() {
		$sql = "SELECT posts.* FROM ".$this->table." AS posts
			ORDER BY GREATEST(posts.dateModified, posts.dateCreated) DESC, posts.id_PK DESC";
		return $this->execSQL($sql);
	}

	public function allActivePosts() {
		$sql = "SELECT posts.*, postsMultiPages.name FROM ".$this->table." AS posts
			LEFT JOIN ".$this->tablePrefix."postsMultiPages AS postsMultiPages ON posts.postMultiPagesId_FK = postsMultiPages.id_PK
			WHERE status = ".ACTIVE."
			ORDER BY GREATEST(posts.dateModified, posts.dateCreated) DESC, posts.id_PK DESC";
		return $this->execSQL($sql);
	}

	public function allPostsInRange($startPost, $nbOfPostsPerPage) {
		$sql = "SELECT posts.* FROM ".$this->table." AS posts
			ORDER BY GREATEST(posts.dateModified, posts.dateCreated) DESC, posts.id_PK DESC
			LIMIT :startPost, :nbOfPostsPerPage";
		$params = array();
		$params[] = array('param' => ':startPost', 'value' => intval($startPost), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':nbOfPostsPerPage', 'value' => intval($nbOfPostsPerPage), 'type' => \PDO::PARAM_INT);		
		return $this->execSQL($sql, $params);
	}

	public function posts($category, $startPost, $nbOfPostsPerPage) {
		$sql = "SELECT posts.*, postsMultiPages.name, postsMultiPages.label FROM ".$this->table." AS posts
			LEFT JOIN ".$this->tablePrefix."categories AS categories ON categories.name = :category AND categories.status = ".ACTIVE."
			LEFT JOIN ".$this->tablePrefix."postsCategories AS postsR ON postsR.categoryId_FK = categories.id_PK
			LEFT JOIN ".$this->tablePrefix."postsMultiPages AS postsMultiPages ON posts.postMultiPagesId_FK = postsMultiPages.id_PK
			WHERE (postsR.postId_FK = posts.id_PK OR (postsR.postsMultiPagesId_FK = posts.postMultiPagesId_FK AND posts.lft = 1))
				AND posts.status = ".ACTIVE."
			ORDER BY GREATEST(posts.dateModified, posts.dateCreated) DESC, posts.id_PK DESC
			LIMIT :startPost, :nbOfPostsPerPage";
		$params = array();
		$params[] = array('param' => ':category', 'value' => $category, 'type' => \PDO::PARAM_STR);	
		$params[] = array('param' => ':startPost', 'value' => intval($startPost), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':nbOfPostsPerPage', 'value' => intval($nbOfPostsPerPage), 'type' => \PDO::PARAM_INT);		
		return $this->execSQL($sql, $params);
	}

	public function categoryPostsMultiPages($postMultiPagesId) {
		$sql = "SELECT categories.name FROM ".$this->tablePrefix."categories AS categories
			LEFT JOIN ".$this->tablePrefix."postsCategories AS postsR ON postsR.categoryId_FK = categories.id_PK
			WHERE postsR.postsMultiPagesId_FK = :postsRPostId";
		$params = array();
		$params[] = array('param' => ':postsRPostId', 'value' => intval($postMultiPagesId), 'type' => \PDO::PARAM_INT);	
		//$params[] = array('param' => ':postId', 'value' => intval($postMultiPagesId), 'type' => \PDO::PARAM_INT);	
		return $this->execSQL($sql, $params);
	}

	public function category($postId) {
		$sql = "SELECT categories.name FROM ".$this->tablePrefix."categories AS categories
			LEFT JOIN ".$this->tablePrefix."postsCategories AS postsR ON postsR.categoryId_FK = categories.id_PK
			WHERE postsR.postId_FK = :postsRPostId";
		$params = array();
		$params[] = array('param' => ':postsRPostId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);	
		//$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);	
		return $this->execSQL($sql, $params);
	}

	public function categoryExist($category) {
		$sql = "SELECT name FROM ".$this->tablePrefix."categories
			WHERE name = :category AND status = ".ACTIVE;
		$params = array();
		$params[] = array('param' => ':category', 'value' => $category, 'type' => \PDO::PARAM_STR);	
		return $this->execSQL($sql, $params);
	}

	public function post($postId) {
		$sql = "SELECT post.*, postsMultiPages.id_PK AS postsMultiPagesId, postsMultiPages.name, postsMultiPages.label FROM ".$this->table." AS post 
			LEFT JOIN ".$this->tablePrefix."postsMultiPages AS postsMultiPages ON post.postMultiPagesId_FK = postsMultiPages.id_PK
			WHERE post.id_PK = :postId AND status = ".ACTIVE;
		$params = array();
		$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);	
		return $this->execSQL($sql, $params);
	}

	public function parentTitle($postsMultiPagesId, $lft, $rgt) {
		$sql = "SELECT post.title AS parentTitle FROM ".$this->table." AS post 
			LEFT JOIN ".$this->tablePrefix."postsMultiPages AS postsMultiPages ON post.postMultiPagesId_FK = postsMultiPages.id_PK
			WHERE post.postMultiPagesId_FK = :postsMultiPagesId AND status = ".ACTIVE."
			AND post.lft > 1 AND post.lft < :lft AND post.rgt > :rgt
			ORDER BY post.lft DESC";
		$params = array();
		$params[] = array('param' => ':postsMultiPagesId', 'value' => intval($postsMultiPagesId), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':lft', 'value' => intval($lft), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':rgt', 'value' => intval($rgt), 'type' => \PDO::PARAM_INT);		
		return $this->execSQL($sql, $params);
	}

	public function summary($postId) {
		$sql = "SELECT posts.id_PK, posts.lft, posts.rgt, posts.title, posts.slug FROM ".$this->table." AS posts 
		LEFT JOIN ".$this->table." AS posts2 ON posts2.id_PK = :postId
		WHERE posts.postMultiPagesId_FK = posts2.postMultiPagesId_FK AND posts.status = ".ACTIVE.'
		ORDER BY posts.lft ASC';
		$params = array();
		$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);	
		return $this->execSQL($sql, $params);
	}

	public function prevPost($category, $postId, $date) {
		$sql = "SELECT posts.id_PK, posts.slug, posts.title FROM ".$this->table." AS posts
			LEFT JOIN ".$this->tablePrefix."categories AS categories ON categories.name = :category AND categories.status = ".ACTIVE."
			LEFT JOIN ".$this->tablePrefix."postsCategories AS postsR ON postsR.categoryId_FK = categories.id_PK
			WHERE postsR.postId_FK = posts.id_PK AND posts.id_PK != :postId AND posts.status = ".ACTIVE." AND 
			(
				(posts.dateModified != '0000-00-00 00:00:00' AND (posts.dateModified > :date OR 
					(posts.dateModified = :date1 AND posts.id_PK > :postId1)))
				OR
				(posts.dateCreated > :date2 OR 
					(posts.dateCreated = :date3 AND posts.id_PK > :postId2))
			)
			ORDER BY GREATEST(posts.dateModified, posts.dateCreated) ASC, posts.id_PK ASC
			LIMIT 1";
		$params = array();
		$params[] = array('param' => ':category', 'value' => $category, 'type' => \PDO::PARAM_STR);	
		$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);	
		$params[] = array('param' => ':date', 'value' => $date, 'type' => \PDO::PARAM_STR);	
		$params[] = array('param' => ':date1', 'value' => $date, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':postId1', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':date2', 'value' => $date, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':date3', 'value' => $date, 'type' => \PDO::PARAM_STR);	
		$params[] = array('param' => ':postId2', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);
		return $this->execSQL($sql, $params);	
	}

	public function nextPost($category, $postId, $date) {
		$sql = "SELECT posts.id_PK, posts.slug, posts.title FROM ".$this->table." AS posts
			LEFT JOIN ".$this->tablePrefix."categories AS categories ON categories.name = :category AND categories.status = ".ACTIVE."
			LEFT JOIN ".$this->tablePrefix."postsCategories AS postsR ON postsR.categoryId_FK = categories.id_PK
			WHERE postsR.postId_FK = posts.id_PK AND posts.id_PK != :postId AND posts.status = ".ACTIVE." AND
			(
				(posts.dateModified != '0000-00-00 00:00:00' AND (posts.dateModified < :date OR
					(posts.dateModified = :date1 AND posts.id_PK < :postId1)))
				OR
				((posts.dateCreated < :date2 AND posts.dateModified < :date3) OR
					(posts.dateCreated = :date4 AND posts.dateModified < :date5 AND posts.id_PK < :postId2))
			) 
			ORDER BY GREATEST(posts.dateModified, posts.dateCreated) DESC, posts.id_PK DESC
			LIMIT 1";
		$params = array();
		$params[] = array('param' => ':category', 'value' => $category, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);	
		$params[] = array('param' => ':date', 'value' => $date, 'type' => \PDO::PARAM_STR);	
		$params[] = array('param' => ':date1', 'value' => $date, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':postId1', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':date2', 'value' => $date, 'type' => \PDO::PARAM_STR);	
		$params[] = array('param' => ':date3', 'value' => $date, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':date4', 'value' => $date, 'type' => \PDO::PARAM_STR);	
		$params[] = array('param' => ':date5', 'value' => $date, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':postId2', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);
		return $this->execSQL($sql, $params);
	}

	public function imageData($where) {
		$sql = "SELECT id_PK, filePath, fileName, source, sourceUrl FROM ".$this->tablePrefix."medias WHERE ".$where;
		return $this->execSQL($sql);
	}

	public function postAuthor($userId) {
		$sql = "SELECT name FROM ".$this->tablePrefix."users WHERE id_PK = :userId AND status = ".ACTIVE;
		$params = array();
		$params[] = array('param' => ':userId', 'value' => intval($userId), 'type' => \PDO::PARAM_INT);
		return $this->execSQL($sql, $params);
	}

	public function incrementView($postId) {
		$sql = "UPDATE ".$this->table." SET views = views + 1 WHERE id_PK = :postId";
		$params = array();
		$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);
		return $this->execSQL($sql, $params);
	}

	public function update($postId, $title, $slug, $imageId, $source, $sourceUrl, $content) {
		$sql = "UPDATE ".$this->table." 
			SET	title = :title, content = :content, slug = :slug, imageId = :imageId, source = :source, sourceUrl = :sourceUrl, dateModified = NOW()
		    WHERE id_PK = :postId"; 
		$params = array();
		$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':title', 'value' => $title, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':slug', 'value' => $slug, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':imageId', 'value' => $imageId, 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':source', 'value' => $source, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':sourceUrl', 'value' => $sourceUrl, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':content', 'value' => $content, 'type' => \PDO::PARAM_INT);
		return $this->execSQL($sql, $params);	
	}

	public function add($title, $content, $slug, $imageId, $source, $sourceUrl, $user) {
		$sql = "INSERT INTO ".$this->table." (title, content, slug, imageId, source, sourceUrl, userId_FK, status, dateCreated) VALUES (:title, :content, :slug, :imageId, :source, :sourceUrl, :user, 1, NOW())";
		$params = array();
		$params[] = array('param' => ':title', 'value' => $title, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':content', 'value' => $content, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':slug', 'value' => $slug, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':imageId', 'value' => $imageId, 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':source', 'value' => $source, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':sourceUrl', 'value' => $sourceUrl, 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':user', 'value' => $user, 'type' => \PDO::PARAM_INT);
		$this->execSQL($sql, $params);
		$newPostId = $this->Db()->lastInsertId();
		$sql = "INSERT INTO ".$this->tablePrefix."postsCategories (postId_FK, categoryId_FK) VALUES (".$newPostId.", 2)";
		$this->execSQL($sql);
		return $newPostId;	
	}

	public function delete($postId) {
		$sql = "DELETE FROM ".$this->table."
			WHERE post.id_PK = :postId";
		$params = array();
		$params[] = array('param' => ':postId', 'value' => intval($postId), 'type' => \PDO::PARAM_INT);	
		return $this->execSQL($sql, $params);
	}
}




/**
 * OLD WAY TO DO SUMMARY (eg. 1-1-1 // 2-1 // etc...)
 */
/***************
public function summaryFindFirstPost($vars) {
    $sql = "SELECT id, parentId
              FROM ".$this->table." as posts
               WHERE id = ".$vars;
    return $this->execSQL($sql);
  }

  public function summary($vars) {
    $sql = "SELECT posts.id as postsId, posts.title as postsTitle, posts.slug as postsSlug, posts.parentId as postsParentId,
    postsSub.id as postsSubId, postsSub.Title as postsSubTitle, postsSub.slug as postsSubSlug, postsSub.parentId as postsSubParentId, 
    postsSubSub.id as postsSubSubId, postsSubSub.Title as postsSubSubTitle, postsSubSub.slug as postsSubSubSlug, postsSubSub.parentId as postsSubSubParentId
                      FROM ".$this->table." as posts
                      LEFT JOIN ".$this->table." as postsSub ON postsSub.parentId = posts.id
                      LEFT JOIN ".$this->table." as postsSubSub ON postsSubSub.parentId = postsSub.id
                       WHERE posts.id = ".$vars."
                      ORDER BY posts.pagination ASC, postsSub.pagination ASC, postsSubSub.pagination ASC";
    return $this->execSQL($sql);
}
***************/