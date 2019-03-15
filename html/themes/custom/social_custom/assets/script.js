jQuery(document).ready(function () {
	if(jQuery('.slider-home').length > 0) {
		jQuery('.slider-home').slick();
	}	
	if(jQuery('.slider-home-content').length > 0) {
		jQuery('.slider-home-content').slick();
	}	
	if(jQuery('.dropdown-menu .language-link').length) {
		jQuery('.dropdown-menu .language-link').each(function () {
			var href = jQuery(this).attr('href');
			if(href !== undefined) {
				var new_url;
				var lang = jQuery(this).attr('hreflang');
				if (!drupalSettings.path.isFront) {
				  new_url = drupalSettings.path.baseUrl + drupalSettings.path.currentPath + '?langdrauta=' + lang;
				}
				else {
  				new_url = drupalSettings.path.baseUrl + '?langdrauta=' + lang;
				}
								
				jQuery(this).attr('href', new_url);
			}
		});		
	}

});

