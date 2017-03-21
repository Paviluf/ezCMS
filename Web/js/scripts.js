// Menu toggle
function toggleHeaderMenu(divID) {
    var item = document.getElementById(divID);
    if (item) {
      item.className=(item.className=="menu-header-a")?"menu-header-b":"menu-header-a";
	}
}

// Toggle
function toggle(className) {
    var item = document.getElementsByClassName(className);
	i = item.length;

    while(i--) {
		item[i].style.display = (item[i].style.display == "") ? "none" : "";     
    }
}

// Detect adblock and display a message
/*
<div style="position:absolute;top:-10px;right:-15px;font-size:25px;font-weight:bold;cursor:pointer;" onClick="closeMsg()">X</div><div style="width:245px;height:144px;margin:auto;background-image:url(/medias/meme.jpg);"></div><h1>Hey you know what, I block ads too!</h1><p>I\'m like you, I don\'t like ads. It\'s annoying and I don\'t want to see them.</p><p>But I don\'t want to pay either when I visit a site, because, like everyone else, the guy who made it (me in this case) don\'t work for free. So I try to be smart and I let the ads be displayed on the sites I like.</p><p><em>I hope you will like the site and you will do the same. Thanks.</em></p>
*/
function adblockBlock() {
	if (document.getElementById('adsBottom') == null) {
		document.getElementById("msgAdblock").innerHTML = 'I notice you\'ve got an ad-blocker switched on. Perhaps you\'d like to disable it for Ezoden, or support Ezoden another way, like <a href="/2/support">making a small donation.</a>';

		document.getElementById("msgAdblock").style.display = 'block';
	}
}

// Close adblock message
function closeMsg() {
	document.getElementById("msgAdblock").style.display = 'none';
}