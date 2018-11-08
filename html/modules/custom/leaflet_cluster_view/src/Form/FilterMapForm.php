<?php

   namespace Drupal\leaflet_cluster_view\Form;

   use Drupal\Core\Form\FormBase;
   use Drupal\Core\Form\FormStateInterface;

   class FilterMapForm extends FormBase {

     public function getFormId() {
       return 'leaflet_cluster_view_form'; // ES el ID único del módulo.
     }

     public function buildForm(array $form, FormStateInterface $form_state) {
	   $form_state->setMethod('get');
	   $values = $form_state->getValues();
       
       if(empty($values) && !empty($_GET['typeview'])) {
	   	    $values['typeview'] = $_GET['typeview'];
	   	    $values['ambits'] = isset($_GET['ambits']) ? $_GET['ambits'] : '';
       }
       
       $form['name'] = [
         '#type' => 'textfield',
         '#title' => $this->t('Name'),
         '#size' => 60,
         '#maxlength' => 10,
         '#default_value' => isset($_GET['name']) ? $_GET['name'] : '',
       ];
       
       
       $form['typeview'] = [
	    '#type' => 'select',
	    '#title' => $this->t('Type'),
	    '#empty_value' => '',
	    '#empty_option' => '- Select a value -',
	    '#default_value' => (isset($values['my_select']) ? $values['my_select'] : ''),
	    '#options' => [
	      'entities' => t('Entities'),
		  'public_group' => t('Projects'),
		  'event' => t('Events'),
	    ],
	    '#default_value' => isset($_GET['typeview']) ? $_GET['typeview'] : '',
	    '#ajax' => [
	      'callback' => [$this, 'typeviewChange'],
	      'event' => 'change',
	      'wrapper' => 'my-ajax-wrapper',
	    ],
	   ];      
	   
	   
	   $form['my-ajax-wrapper'] = [
		'#type' => 'container',
			'#attributes' => [
			'id' => 'my-ajax-wrapper',
		]
	   ];
	   
	   
	   if(!empty($values) && !empty($values['typeview'])) {
		   if($values['typeview'] != 'event') {
			   // Si es entidad o proyecto.
			   $form['my-ajax-wrapper']['ambits'] = [
			    '#type' => 'select',
			    '#title' => $this->t('Scope'),
			    '#empty_value' => '',
			    '#empty_option' => '- Select a value -',
			    '#default_value' => (isset($values['ambits']) ? $values['ambits'] : ''),
			    '#options' => $this->getAmbits(),
			   ]; 
		      
		   }		  
	   }

       $form['actions']['submit'] = [
         '#type' => 'submit',
         '#value' => $this->t('Send'),
       ];

       return $form;
     }
	 
	 public function typeviewChange($form, $form_state) {
		 return $form['my-ajax-wrapper'];
	 }
     public function submitForm(array &$form, FormStateInterface $form_state) {
        
     }
     
	 public function validateForm(array &$form, FormStateInterface $form_state) {
      
     }
    
    /**
	 * GetAmbits().
	 */
    public function getAmbits() {
	  $data = &drupal_static(__FUNCTION__);
	    
	  $cid = 'leaflet_cluster_view_form' . \Drupal::languageManager()->getCurrentLanguage()->getId();
	    
	  if ($cache = \Drupal::cache()->get($cid)) {
	   $data = $cache->data;
	  } else {
	   $retorno = [];	    
	   $vid = 'ambito_del_proyecto';		
	   $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);		
	   
	   foreach ($terms as $term) {
		 $retorno[$term->tid] = $term->name;		 
	    }	
	    $data = $retorno;	
	    
	   \Drupal::cache()->set($cid, $data, REQUEST_TIME + (3600));
	  }
		return $data;
	}  
     
}