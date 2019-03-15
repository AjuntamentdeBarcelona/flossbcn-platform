Drupal.behaviors.custom_recaptcha = {
  attach: function (context, settings) {
  	var siteKey = drupalSettings.site_key;  	
  	grecaptcha.ready(function() {	  	
		grecaptcha.execute(siteKey, {action: 'homepage'})
		.then(function(token) {
			var forms = drupalSettings.forms;	
			for(var k in forms) {
				var currentSelector = jQuery('[id*=' + forms[k] + ']');
				if(currentSelector.length && !jQuery('[id*=' + forms[k] + '] input[name="recaptchaCustom"]').length) {
					jQuery('[id*=' + forms[k] + ']').append('<input name="recaptchaCustom" type="hidden" value="' +  token + '"/>' );
				}
			}			
		});		
	});
  }
};