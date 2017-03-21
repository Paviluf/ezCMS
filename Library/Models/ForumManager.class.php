<?php

/*
 * Get Forum Data
 */

namespace Library\Models;

class ForumManager extends \Library\Model {
	public function latestPostsFromForum($nbOfPosts) {
		$sql = "SELECT phpbb_topics.topic_id, phpbb_topics.forum_id, phpbb_topics.topic_title FROM phpbb_topics, phpbb_forums WHERE phpbb_topics.forum_id=phpbb_forums.forum_id ORDER BY phpbb_topics.topic_last_post_time DESC LIMIT ".$nbOfPosts;	 
		return $this->execSQL($sql);
	}
}
