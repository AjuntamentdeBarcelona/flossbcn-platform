<?php
namespace Drupal\custom_recaptcha\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class ConfigForm extends ConfigFormBase {
  protected $number = 1;
  
  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_recaptcha_configuration';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_recaptcha_configuration.settings',
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_recaptcha_configuration.settings');

    $form['site_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Api key'),
      '#default_value' => $config->get('site_key'),
    );  
    $form['secret_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Secret key'),
      '#default_value' => $config->get('secret_key'),
    );
    
    $form['forms_id'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Forms Id'),
      '#default_value' => $config->get('forms_id'),
      '#rows' => 8
    );
    
    
    
    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	  
    // Save configuration.
    $config_factory =  $this->configFactory->getEditable('custom_recaptcha_configuration.settings');      
    $config_factory->set('site_key', trim($form_state->getValue('site_key')))->save();
    $config_factory->set('secret_key', trim($form_state->getValue('secret_key')))->save();
	$config_factory->set('forms_id', trim($form_state->getValue('forms_id')))->save();
	parent::submitForm($form, $form_state);
  }
}