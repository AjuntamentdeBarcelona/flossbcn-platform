<?php
namespace Drupal\custom_counters;
/**
 * extend Drupal's Twig_Extension class
 */
class CustomTwigExtensions extends \Twig_Extension {
  /**
   * {@inheritdoc}
   * Let Drupal know the name of your extension
   * must be unique name, string
   */
  public function getName() {
    return 'custom_counters.basetwig';
  }
  /**
   * {@inheritdoc}
   * Return your custom twig function to Drupal
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('get_entity_number', [$this, 'get_entity_number']),
    ];
  }
  
  public static function get_entity_number($type_entity, $type_entity_type) {	
	  $num = 0;	  
	  $cid = 'custom_counter_' . $type_entity . '_' . $type_entity_type;
	  
	  if ($cache = \Drupal::cache()->get($cid)) {
		  $num = $cache->data;
	  }
	  else {
		  
		  $element = 'id';
		  if($type_entity == 'node') {
			 $element = 'nid'; 
		  }
		  $db = \Drupal::database();  
		  
		  $query = $db->select($type_entity,'n')
		  ->fields('n', [$element])
		  ->condition('n.type', $type_entity_type);
		  
		  $result = $query->execute()->fetchAll();
		  
		  if(!empty($result) && is_array($result)) {
			   $num = count($result);
		  } 
		  
		  \Drupal::cache()->set($cid, $num, \Drupal::time()->getRequestTime() + 300);
	  }
	  
	  

	  return $num;
  }
  
 }
