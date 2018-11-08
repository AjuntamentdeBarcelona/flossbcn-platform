jQuery(document).ready(function () {
	if(jQuery('.slider-home').length > 0) {
		jQuery('.slider-home').slick();
	}	
	if(jQuery('.dropdown-menu .language-link').length) {
		jQuery('.dropdown-menu .language-link').each(function () {
			var href = jQuery(this).attr('href');
			if(href !== undefined) {
				var new_url = href.substring(0, href.indexOf('?'));
				var lang = jQuery(this).attr('hreflang');
				new_url = new_url + '?langdrauta=' + lang;
				jQuery(this).attr('href', new_url);
			}			 
		});
	}
});