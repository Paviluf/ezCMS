<?php

$html = '<p><a href="/admin/post/add">ADD NEWS</a></p>';
foreach ($data as $post) {
	if(is_array($post)) {
		$html .= '<a href="/admin/post/update/';
		$html .= $post['id_PK'].'">
				<p>
					'.$post['title'].' - ('.$post['views'].')
				</p>
			</a>';
	}
}