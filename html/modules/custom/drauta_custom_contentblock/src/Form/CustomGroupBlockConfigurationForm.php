<?php

namespace Drupal\drauta_custom_contentblock\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure social links for this site.
 */
class SocialLinksConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drauta_custom_contentblock_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'drauta_custom_contentblock.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('drauta_custom_contentblock.config');

  
    
   
      $form['drauta_custom_contentblock_'] = array(
        '#type' => 'textfield', 
        '#title' => $title, 
        '#default_value' => $config->get('drauta_custom_contentblock_' . $key),
        '#size' => 60, 
        '#maxlength' => 128,
      );
    

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

	foreach($values as $value) {
      $this->config('drauta_custom_contentblock.config')
           ->set('drauta_custom_contentblock_title', $value)
           ->save();
    }
    
    parent::submitForm($form, $form_state);
  }

}
