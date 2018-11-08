<?php

namespace Drupal\leaflet_cluster_view\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Drupal\Core\Url;

/**
 * Class DataController.
 */
class DataController extends ControllerBase {

  public function getElementsAction(Request $request) {
	$type = "all";  	
  	$name = $ambits = "";
  	 	
  	if(!empty($_GET)) {
	  if(!empty($_GET['name']) && $_GET['name'] != 'null') {
		$name = $_GET['name']; 
	  }
	  if(!empty($_GET['type']) && $_GET['type'] != 'null' ) {
		  $type = trim($_GET['type']);
	  }
	  if(!empty($_GET['ambits']) && $_GET['ambits'] != 'null' ) {
		  $ambits = trim($_GET['ambits']);
		  
	  }
  	}
  	
  	if($type == 'all') { // All
	  	$db = \Drupal::database();
	  	$query = $db->select('group__field_geolocation','gfg');
	  	$query->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
	  	$query->leftJoin('groups_field_data', 'gfd', 'gfd.id = gfg.entity_id');
	  	$query->fields('gfd', array('label', 'type'));
	  		  	
	  	if($name != '') {
		  	$query->condition('gfd.label', '%' . $name . '%', 'LIKE');
	  	}
		
	  	// EVENTOS
	  	$query2 = $db->select('node__field_geolocation','gfg');
	  	$query2->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
	  	$query2->leftJoin('node_field_data', 'nfd', 'nfd.nid = gfg.entity_id');
	  	$query2->fields('nfd', array('title', 'type'));
	  	if($name != '') {
		  	$query2->condition('nfd.title', '%' . $name . '%', 'LIKE');
	  	}		
	  	$query->union($query2);		
	  	  	
  	} else if($type == 'entities' || $type == 'public_group') { // Projects and Orgs
	  
	  $db = \Drupal::database();
	  $query = $db->select('group__field_geolocation','gfg');
	  $query->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
	  $query->leftJoin('groups_field_data', 'gfd', 'gfd.id = gfg.entity_id');
	  $query->fields('gfd', array('label', 'type'));
	  
	  if($type == 'entities' && $ambits != '') {
		  
		  $query->leftJoin('group__field_scope_of_action', 'gfsa', 'gfd.id = gfsa.entity_id');
		  $query->condition('gfsa.field_scope_of_action_target_id', $ambits);  
	  }	
	  
	  
	  if($type == 'public_group' && $ambits != '') {
		  $query->leftJoin('group__field_project_areas', 'gfpa', 'gfd.id = gfpa.entity_id');
		  $query->condition('gfpa.field_project_areas_target_id', $ambits); 
	  }	
	  
	  
	  $query->condition('gfg.bundle', $type);  
	  
	  if($name != '') {
		$query->condition('gfd.label', '%' . $name . '%', 'LIKE');
	  }
	  
	  
	  	
	  
	  // field_project_areas --> public_group group__field_project_areas
	  // field_scope_of_action --> entities.  group__field_scope_of_action
	  
	  
	  	
  	} else if($type == 'event') {	  	// Events
	  	
	  	$db = \Drupal::database();
	  	$query = $db->select('node__field_geolocation','gfg');
	  	$query->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
	  	$query->leftJoin('node_field_data', 'nfd', 'nfd.nid = gfg.entity_id');
	  	$query->fields('nfd', array('title', 'type'));  
	  	if($name != '') {
		  	$query->condition('nfd.title', '%' . $name . '%', 'LIKE');
		} 	
  	}
  	
  	
  	
  	
  	
  	
  	/*
  	
  	// ORGANIZACIONES Y PROYECTOS
    $db = \Drupal::database();
    $query = $db->select('group__field_geolocation','gfg');
	$query->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
    $query->leftJoin('groups_field_data', 'gfd', 'gfd.id = gfg.entity_id');
    $query->fields('gfd', array('label', 'type'));
    // $query->condition('gfg.bundle', 'organization');  
		
	// EVENTOS
	$query2 = $db->select('node__field_geolocation','gfg');
	$query2->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
	$query2->leftJoin('node_field_data', 'nfd', 'nfd.nid = gfg.entity_id');
	$query2->fields('nfd', array('title', 'type'));
		
	$query->union($query2); */
	
	$result = $query->execute();
    $pintados = $result->fetchAll();
		
    return new JsonResponse($pintados);
  }
  
  public function getRouteLinkAction(Request $request, $name) {	  
	  $array_url = explode('-', $name);	  
	  $route_name = '';	 
	  if(!empty($array_url[0]) && !empty($array_url[1])) {
		  if($array_url[0] == 'event') {			
		  	return $this->redirect('entity.node.canonical', ['node' => $array_url[1]]);
		  } else {			  
			   // return $this->redirect('entity.group.canonical', ['group' => $array_url[1]]);			   
			   return $this->redirect('view.group_information.page_group_about', ['view_id' => 'group_information' ,'display_id' => 'page_group_about', 'group' => $array_url[1]]);
		  }	
	  } else {
		  return $this->redirect('<front>');
	  }		      
  }
  
  public function getElementsOrganitzacionsAction(Request $request) {
  	$db = \Drupal::database();
    $query = $db->select('group__field_geolocation','gfg');
	$query->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
    $query->leftJoin('groups_field_data', 'gfd', 'gfd.id = gfg.entity_id');
    $query->fields('gfd', array('label', 'type'));
    $query->condition('gfg.bundle', 'entities');  
    
    $result = $query->execute();
    $pintados = $result->fetchAll();
		
    return new JsonResponse($pintados);
  }
  
  public function getElementsProjectsAction(Request $request) {
	  
	$db = \Drupal::database();
    $query = $db->select('group__field_geolocation','gfg');
	$query->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
    $query->leftJoin('groups_field_data', 'gfd', 'gfd.id = gfg.entity_id');
    $query->fields('gfd', array('label', 'type'));
    $query->condition('gfg.bundle', 'public_group');  
    
    $result = $query->execute();
    $pintados = $result->fetchAll();
		
    return new JsonResponse($pintados);
  }
  
  public function getElementsEventsAction(Request $request) {
	$db = \Drupal::database();
	$query = $db->select('node__field_geolocation','gfg');
	$query->fields('gfg', array('entity_id', 'field_geolocation_lat', 'field_geolocation_lng'));
	$query->leftJoin('node_field_data', 'nfd', 'nfd.nid = gfg.entity_id');
	$query->fields('nfd', array('title', 'type'));
	
	$result = $query->execute();
    $pintados = $result->fetchAll();
		
    return new JsonResponse($pintados);
	  
  }
  
}
