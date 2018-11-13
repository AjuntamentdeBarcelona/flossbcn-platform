<?php
   namespace Drupal\drauta_alters;

  class IconUser extends \Twig_Extension{
  	
  	public function getName() {
    	return 'drauta_alters.print_icon';
  	}
  	public function getFunctions() {
    	return [
		new \Twig_SimpleFunction('get_url_param', [$this, 'get_url_param']),
		];
  	}
  	public static function get_url_param($user_reference, $image_user) {
	  	
	   if(!empty($image_user) && $image_user != '/') {
		   $style = \Drupal::entityTypeManager()->getStorage('image_style')->load('social_large');
		   $url = $style->buildUrl($image_user);
		   
		   $code = '<img class="image-responsive-redonda" src="' . $url . '" />';		  	
	  	} else {
		  	$user = user_load($user_reference);
		  	$user_name = $user->getUsername();
		  	
		  	$primera_letra = mb_substr($user_name, 0, 1, "utf-8");
		  	$primera_letra_numero = IconUser::toNumber($primera_letra);
		  	
		  	$class = "multiple";
		  	
		  	if($primera_letra_numero % 4 == 0) {
			  	$class = "multiple-4";
		  	} else if($primera_letra_numero % 3 == 0) {
			  	$class = "multiple-3";
		  	} else if($primera_letra_numero % 2 == 0) {
			  	$class = "multiple-2";
		  	}	  	
		  	
		  	$code = "<div class='icono-redonda ". $class . "'><span>$primera_letra</span></div>";
	  	
	  	}
	  	
	  	
	  	
	  	
    	return $code; 
  	}
  	
  	public static function toNumber($dest)
    {
        if ($dest)
            return ord(strtolower($dest)) - 96;
        else
            return 0;
    }

  }
