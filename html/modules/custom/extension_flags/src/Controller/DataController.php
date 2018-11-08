<?php
namespace Drupal\extension_flags\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Url;
use Drupal\Component\Render\FormattableMarkup;  


class DataController extends ControllerBase {

 public function stream(Request $request) {
	 $serviceTable = \Drupal::service('extension_flags.table');
	 $db = \Drupal::database();
	 $query = $db->select('flagging','f');
	 $query->fields('f', array('entity_id', 'flag_id'));	
	
	 $query->join('post__field_post', 'pfp', 'f.entity_id = pfp.entity_id');
	$query->fields('pfp', array('field_post_value'));
		
	$query->condition('f.flag_id', 'stream_report');
	
	return $serviceTable->create_table($query, 'post');
	
	
   /*  $header = array(
	  array('data' => t('Stream')),
	  array('data' => t('Link disable report')),
	  array('data' => t('Link to content')),	  
	);
	
	
	$db = \Drupal::database();
	$query = $db->select('flagging','f');
	$query->fields('f', array('entity_id', 'flag_id'));	
	
	$query->join('post__field_post', 'pfp', 'f.entity_id = pfp.entity_id');
	$query->fields('pfp', array('field_post_value'));
		
	$query->condition('f.flag_id', 'stream_report');
	
	$pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(10);
	
	$result = $pager->execute();
	
	$rows = array();
	
	
	$current_path = \Drupal::service('path.current')->getPath();
	$destination_path = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
	
	
	foreach($result as $row) {
		$url = Url::fromRoute('flag.action_link_unflag', ['flag' => $row->flag_id, 'entity_id' =>  $row->entity_id], ['query' => ['destination' => $destination_path]])->toString();	 	
		$url_content = Url::fromRoute('entity.post.canonical', ['post' => $row->entity_id ])->toString();
		
		$rows[] = array('data' => array(
	      'content' => $row->field_post_value,
	      'link_disable_disable' =>  new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url, '@name' => 'Disable report']),
	      'link_content' => new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url_content, '@name' => 'View'])
		));		
	}	
	
	$build = array(
	  '#markup' => t('')
	);
	
	$build['config_table'] = array(
	 '#theme' => 'table', 
  	 '#header' => $header,
  	 '#rows' => $rows,
  	);
  	
  	$build['pager'] = array(
	  '#type' => 'pager'
	);
	
	return $build; */
	
 }
 
 
 
 public function groups(Request $request) {
	 $serviceTable = \Drupal::service('extension_flags.table');
	$db = \Drupal::database();
	$query = $db->select('flagging','f');	
	$query->join('groups_field_data', 'gfd', 'gfd.id = f.entity_id');
	
	$query->fields('f', array('entity_id', 'flag_id'));
	$query->fields('gfd', array('label'));		
	$query->condition('f.flag_id', 'report_group'); 
	 
	return $serviceTable->create_table($query, 'group');
	 
	
	/* $flag_id = 'report_group';	
	
	
	$header = array(
	  array('data' => t('Entity or Project')),
	  array('data' => t('Link disable report')),
	  array('data' => t('Link to content')),	  
	);
	
	
	$db = \Drupal::database();
	$query = $db->select('flagging','f');	
	$query->join('groups_field_data', 'gfd', 'gfd.id = f.entity_id');
	
	$query->fields('f', array('entity_id', 'flag_id'));
	$query->fields('gfd', array('label'));		
	$query->condition('f.flag_id', 'report_group');
	
	
	
                    	
	$pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(10);	
	$result = $pager->execute(); 
	
	$rows = array();
	
	$current_path = \Drupal::service('path.current')->getPath();
	$destination_path = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
	
	
	
	foreach($result as $row) {
	  $url = Url::fromRoute('flag.action_link_unflag', ['flag' => $row->flag_id, 'entity_id' =>  $row->entity_id], ['query' => ['destination' => $destination_path]])->toString();	 
	  
	  $url_content = Url::fromRoute('view.group_information.page_group_about', ['view_id' => 'group_information' , 'display_id' =>  'page_group_about', 'group' => $row->entity_id])->toString();
	  
	  $rows[] = array('data' => array(
	    'content' => $row->label,
	    'link_disable_disable' =>  new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url, '@name' => 'Disable report']),
	    'link_content' => new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url_content, '@name' => 'View'])
	  ));	  	  
	}
	
 	$build = array(
		'#markup' => t('')
	);
	
	$build['config_table'] = array(
	'#theme' => 'table', 
  	'#header' => $header,
  	'#rows' => $rows,
  	);
  	$build['pager'] = array(
	  '#type' => 'pager'
	);
	
	return $build;	*/	 
 }
 
  public function comments() {
	  
	  
	$serviceTable = \Drupal::service('extension_flags.table');
	$db = \Drupal::database();
	$query = $db->select('flagging','f');	
	$query->join('comment__field_comment_body', 'nfcb', 'nfcb.entity_id = nfcb.entity_id');
	
	$query->fields('f', array('entity_id', 'flag_id'));
	$query->fields('nfcb', array('field_comment_body_value'));		
	$query->condition('f.flag_id', 'report_comment');
	 
	 
	return $serviceTable->create_table($query, 'comment');
	 
	 /* $header = array(
	  array('data' => t('Title')),
	  array('data' => t('Link disable report')),
	  array('data' => t('Link to content')),	  
	);
	
	
	
	$db = \Drupal::database();
	$query = $db->select('flagging','f');	
	$query->join('comment__field_comment_body', 'nfcb', 'nfcb.entity_id = nfcb.entity_id');
	
	$query->fields('f', array('entity_id', 'flag_id'));
	$query->fields('nfcb', array('field_comment_body_value'));		
	$query->condition('f.flag_id', 'report_comment');
	
	
	
	
	$pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(10);	
	$result = $pager->execute(); 
	
	$rows = array();
	$current_path = \Drupal::service('path.current')->getPath();
	$destination_path = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
	
	
	foreach($result as $row) {
	  $url = Url::fromRoute('flag.action_link_unflag', ['flag' => $row->flag_id, 'entity_id' =>  $row->entity_id], ['query' => ['destination' => $destination_path]])->toString();	  
	  
	  $url_content = Url::fromRoute('entity.comment.canonical', ['comment' => $row->entity_id])->toString();  
	  
	  
	  $rows[] = array('data' => array(
	    'content' => $row->field_comment_body_value,
	    'link_disable_disable' =>  new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url, '@name' => 'Disable report']),
	    'link_content' => new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url_content, '@name' => 'View'])
	  ));	  	  
	} 
	$build = array(
		'#markup' => t('')
	);
	
	$build['config_table'] = array(
	'#theme' => 'table', 
  	'#header' => $header,
  	'#rows' => $rows,
  	);
  	$build['pager'] = array(
	  '#type' => 'pager'
	);
	
	return $build;	*/
	 
 }
 
 public function contents() {
	 $serviceTable = \Drupal::service('extension_flags.table');
	 $db = \Drupal::database();
	 $query = $db->select('flagging','f');	
	 $query->join('node_field_data', 'nfd', 'nfd.nid = f.entity_id');
	
	 $query->fields('f', array('entity_id', 'flag_id'));
	 $query->fields('nfd', array('title'));		
	 $query->condition('f.flag_id', 'report_content');	 
	 
	 return $serviceTable->create_table($query, 'node');
	 
	 
	 
	 
	/* $header = array(
	  array('data' => t('Title')),
	  array('data' => t('Link disable report')),
	  array('data' => t('Link to content')),	  
	);
	
	
	$db = \Drupal::database();
	$query = $db->select('flagging','f');	
	$query->join('node_field_data', 'nfd', 'nfd.nid = f.entity_id');
	
	$query->fields('f', array('entity_id', 'flag_id'));
	$query->fields('nfd', array('title'));		
	$query->condition('f.flag_id', 'report_content');
	
	$pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(10);	
	$result = $pager->execute(); 
	
	$rows = array();
	
	$current_path = \Drupal::service('path.current')->getPath();
	$destination_path = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
	
	
	foreach($result as $row) {
	  $url = Url::fromRoute('flag.action_link_unflag', ['flag' => $row->flag_id, 'entity_id' =>  $row->entity_id], ['query' => ['destination' => $destination_path]])->toString();	 
	  
	  $url_content = Url::fromRoute('entity.node.canonical', ['node' => $row->entity_id])->toString();
	  
	  $rows[] = array('data' => array(
	    'title' => $row->title,
	    'link_disable_disable' =>  new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url, '@name' => 'Disable report']),
	    'link_content' => new FormattableMarkup('<a href=":link">@name</a>', 
        				[':link' => $url_content, '@name' => 'View'])
	  ));	  	  
	}
	
	$build = array(
		'#markup' => t('')
	);
	
	$build['config_table'] = array(
	'#theme' => 'table', 
  	'#header' => $header,
  	'#rows' => $rows,
  	);
  	$build['pager'] = array(
	  '#type' => 'pager'
	);
	
	return $build;	 */	 
 }
 

}
?>