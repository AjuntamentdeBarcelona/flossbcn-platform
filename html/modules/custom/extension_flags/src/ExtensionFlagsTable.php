<?php
  namespace Drupal\extension_flags;
  use Drupal\Core\Url;
  use Drupal\Component\Render\FormattableMarkup; 
  class ExtensionFlagsTable {
	public function create_table($query, $type_element) {
		$header = array(
		  array('data' => t('Content')),
		  array('data' => t('Link disable report')),
		  array('data' => t('Link to content')),	  
      array('data' => t('Author')),	  
		);
		
		$pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(10);	
		$result = $pager->execute();
		
		$rows = array();
	
		$current_path = \Drupal::service('path.current')->getPath();
		$destination_path = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
		
		foreach($result as $row) {
		  $url = Url::fromRoute('flag.action_link_unflag', ['flag' => $row->flag_id, 'entity_id' =>  $row->entity_id], ['query' => ['destination' => $destination_path]])->toString();	 
		  
		  
		  $url_content = $this->generate_url($type_element, $row->entity_id);
      $author = $row->name;
		  
		  $title_current = '';
		  if(!empty($row->title)) {
			 $title_current = $row->title;
		  } else if(!empty($row->field_post_value)) {
			  $title_current = $row->field_post_value;
		  } else if(!empty($row->field_comment_body_value)) {
			  $title_current = $row->field_comment_body_value;
		  } else if(!empty($row->label)) {
			  $title_current = $row->label;
		  }
		  
		   $rows[] = array('data' => array(
			    'title' => $title_current,
			    'link_disable_disable' =>  new FormattableMarkup('<a href=":link">@name</a>', 
		        				[':link' => $url, '@name' => 'Disable report']),
			    'link_content' => new FormattableMarkup('<a href=":link">@name</a>', 
		        				[':link' => $url_content, '@name' => 'View']),
        'author' => $author,
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
		
		return $build;		
			
	}
	
	
	public function generate_url($type, $entity_id) {
		$url = '';
		
		if($type == 'node') {
		  $url = Url::fromRoute('entity.node.canonical', ['node' => $entity_id])->toString();
		} else if($type == 'group') {
		  $url = Url::fromRoute('view.group_information.page_group_about', ['view_id' => 'group_information' , 'display_id' =>  'page_group_about', 'group' => $entity_id])->toString();
		} else if ($type == 'comment') {
		   $url = Url::fromRoute('entity.comment.canonical', ['comment' => $entity_id])->toString();  
		} else if ($type == 'post') {
		   $url = Url::fromRoute('entity.post.canonical', ['post' => $entity_id])->toString();
		}
		
		return $url;
		
		
	}	  
  }
