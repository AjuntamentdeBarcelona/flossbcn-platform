<?php

namespace Drupal\change_language_drauta\Plugin\LanguageNegotiation;

use Drupal\language\LanguageNegotiationMethodBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Custom class for identifying language.
 *
 * @LanguageNegotiation(
 *   id = Drupal\change_language_drauta\Plugin\LanguageNegotiation\LanguageNegotiation589::METHOD_ID,
 *   weight = -99,
 *   name = @Translation("589 Language Switching"),
 *   description = @Translation("Language based on 589."),
 * )
 */
class LanguageNegotiation589 extends LanguageNegotiationMethodBase {

  /**
   * The language negotiation method id.
   */
  const METHOD_ID = 'language-589';

  /**
   * {@inheritdoc}
   */
  public function getLangcode(Request $request = NULL) {
	 
	$entrar = TRUE;
	$language_selected = null;	
	$current_path = \Drupal::service('path.current')->getPath();
	if (strpos($current_path, '/admin/') !== false || strpos($current_path, '/edit/') !== false || strpos($current_path, '/add/') !== false) {
		$entrar = FALSE;    	
	}
	if($entrar) {
		$tempstore = \Drupal::service('user.private_tempstore')->get('change_lang');	
	
		if(!empty($_GET['langdrauta'])) {
			$language_selected = $_GET['langdrauta'];
			$tempstore->set('language_selected', $language_selected);	
		} else {		
			$language_selected = $tempstore->get('language_selected');		
		}	
	}	
    return $language_selected;
  }

}
