<?php


if(isset($id_PK)) {
	$id = $id_PK;
}
else {
	$id = '';
	$title = '';
	$slug = '';
	$content = '';
	$imageId = '';
	$source = '';
	$sourceUrl = '';
}

$cssPath = 'templates/frontend/'.mb_strtolower($template).'/css/';
$cssStyle = $cssPath.'style.css';
$cssStyle = '/'.$cssStyle.'?ver='.filemtime(WEB_PATH.$cssStyle); 

$cssPath = 'templates/'.mb_strtolower($appName).'/'.mb_strtolower($template).'/css/';
$cssTinymce = $cssPath.'tinymce.css';
$cssTinymce = '/'.$cssTinymce.'?ver='.filemtime(WEB_PATH.$cssTinymce); 


$html = '<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
		content_css : "'.$cssStyle.', '.$cssTinymce.'",
		convert_urls: false,
        selector: "textarea",
        plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu emoticons template textcolor paste textcolor colorpicker textpattern"
        ],

        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | spellchecker | visualchars visualblocks nonbreaking template",

        menubar: false,';

$html .= "toolbar_items_size: 'small',

        style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],

        templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
        ]
});</script>";

if(isset($modType) && $modType == 'add') {
	$html .= '<form action="/admin/post/add" id="form" method="post">';
}
else {
	$html .= '<form action="/admin/post/update/'.$id.'" id="form" method="post">';
}


$html .= '<p>Title : <input type="text" id="title" name="title" size="90" value="'.$title.'" /></p>';    
$html .= '<p>Slug : <input type="text" id="slug" name="slug" size="90" value="'.$slug.'" /></p>';
$html .= '<p>Image id : <input type="text" id="imageId" name="imageId" size="90" value="'.$imageId.'" /></p>';
$html .= '<p>Source : <input type="text" id="source" name="source" size="90" value="'.$source.'" /></p>';
$html .= '<p>Source url : <input type="text" id="sourceUrl" name="sourceUrl" size="90" value="'.$sourceUrl.'" /></p>';

$html .= '<textarea name="content" style="width:100%;height:640px;">'.$content.'</textarea>';
$html .= '<p><input type="submit" value="Submit"/></p>';


if(isset($id_PK)) {
	$html .= '<input type="hidden" name="updatePostId" value="'.$id.'" /></form>';
}
else {
	$html .= '<input type="hidden" name="newPost" value="new" /></form>';
}



