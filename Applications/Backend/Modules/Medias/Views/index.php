<?php

$html = '<p><a href="/admin/media/new">NEW MEDIA</a></p>';

foreach ($data as $media) {
	if(is_array($media)) {
		$html .= '<a href="/admin/media/update/';
		$html .= $media['id_PK'].'">
				<p>
					<img src="'.MEDIAS_URL.$media['filePath'].$media['fileName'].'"   width="100" />'.$media['fileName'].'
				</p>
			</a>';
	}
}