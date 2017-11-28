/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'faq-extension\'">' + entity + '</span>' + html;
	}
	var icons = {
		'faq-iconkeyboard_arrow_right': '&#xe902;',
		'faq-iconicon-gpg': '&#xe901;',
		'faq-iconfaq-icon': '&#xe900;',
		'faq-iconcalendar': '&#xe953;',
		'faq-iconuser': '&#xe971;',
		'faq-iconeye': '&#xe9ce;',
		'faq-iconhappy': '&#xe9df;',
		'faq-iconangry': '&#xe9ed;',
		'faq-iconplus': '&#xea0a;',
		'faq-iconminus': '&#xea0b;',
		'faq-iconsearch': '&#xe986;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/faq-icon[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
