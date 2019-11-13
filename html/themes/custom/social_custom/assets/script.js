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
				  new_url = drupalSettings.path.baseUrl + drupalSettings.path.currentPath + '?langcustom=' + lang;
				}
				else {
  				new_url = drupalSettings.path.baseUrl + '?langcustom=' + lang;
				}
								
				jQuery(this).attr('href', new_url);
			}
		});
		
		// Menu compte usuari desplegable: treiem add group generic pq no funciona i posem especifics.
    jQuery("#block-social-custom-accountheaderblock .dropdown-menu li:last-child()").remove();
    jQuery("#block-social-custom-accountheaderblock .dropdown-menu").append('<li><a href="' + Drupal.url('group/add/entities') + '" title="' + Drupal.t('New Entity') + '">' + Drupal.t('New Entity') + '</a></li>');
    jQuery("#block-social-custom-accountheaderblock .dropdown-menu").append('<li><a href="' + Drupal.url('group/add/public_group') + '" title="' + Drupal.t('New Project') + '">' + Drupal.t('New Project') + '</a></li>');
	}
    jQuery('.node-topic-form #edit-field-content-visibility--wrapper [data-drupal-selector="edit-field-content-visibility-public"]').click();
});

