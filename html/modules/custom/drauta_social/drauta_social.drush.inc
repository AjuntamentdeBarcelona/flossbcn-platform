<?php
/**
* @file Contains the code to generate the custom drush commands.
*/
/**
* Implements hook_drush_command().
*/
function drauta_social_drush_command() {
   $items = array();
   $items['social-update'] = [
     'description' => 'Update social networks items',
     'arguments' => [
       'name' => 'The name of social network to update',
     ],
     'aliases' => ['social:update'],
   ];
   return $items;
  }
/**
* Call back function drush_custom_drush_command_say_hello()
* The call back function name in the  following format
*   drush_{module_name}_{item_id_for_command}()
*/
function drush_drauta_social_social_update($name = '') {
  $parametro_correcto = TRUE;
  
  if($name == "facebook") {
    drauta_social_create_or_updates_nodes_facebook(); 
  } else if($name == "twitter"){
    drauta_social_create_or_updates_nodes_twitter();    
  } else if($name == 'instagram') {
	  drauta_social_create_or_updates_nodes_instagram();
  } else {
    $parametro_correcto = FALSE;
  } 
  
  if($parametro_correcto) {
    drush_print('Se ha actualizado ' . $name . ' !');
    
  } 
	  
  drush_drauta_social_update_clean_nodes_all(); 
	  
	
	 
  
}


function drush_drauta_social_update_clean_nodes($type) {
	$db = \Drupal::database();
	$query = $db->select('node_field_data', 'nfd');
	$query->fields('nfd', array('nid'));
	$query->join('node__field_type', 'nft', 'nft.entity_id = nfd.nid');
	$query->condition('nfd.type', 'social_content');
	$query->condition('nft.field_type_value', $type);
	$query->orderBy('nfd.created', 'ASC');
	$result = $query->execute();
	$resultado_array = $result->fetchAll();
	// var_dump($resultado_array);
	// var_dump(count($resultado_array));
	if(!empty($resultado_array)) {
		if(count($resultado_array) > 10) {
			for($i = 0; $i <= 5; $i++) {
				$nid_delete = $resultado_array[$i]->nid;
				$node_deletable = node_load($nid_delete);
				$node_deletable->delete();
			}			
		}
	}	
	
}

function drush_drauta_social_update_clean_nodes_all() {
	drush_drauta_social_update_clean_nodes(0);
	drush_drauta_social_update_clean_nodes(1);
	drush_drauta_social_update_clean_nodes(2); 
}