<?php

namespace Drupal\drauta_custom_contentblock\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "drauta_custom_contentblock_main_block",
 *   admin_label = @Translation("Block to show events and topics"),
 * )
 */
 
class CustomGroupBlock extends BlockBase implements BlockPluginInterface {
  /**
   * {@inheritdoc}
   */
  public function build() {
	$retorno = ''; 
	$node = \Drupal::routeMatch()->getParameter('node');
	if ($node instanceof \Drupal\node\NodeInterface) {
	  // You can get nid and anything else you need from the node object.
	  $nid = $node->id();	  
	  $node = node_load($nid);
	  $group = _social_group_get_current_group($node);
	  $group_content = null;
	  if(!empty($group)) {
		  $view_builder = \Drupal::entityTypeManager()->getViewBuilder('group');
		  $build = $view_builder->view($group, 'teaser'); 
		  $output = render($build);
		  $retorno = $output->__toString();	 
	  }	   
	}	  
	  
	 return array(	    
      '#type' => 'markup',
      '#markup' => $retorno,
    );
  } 

}