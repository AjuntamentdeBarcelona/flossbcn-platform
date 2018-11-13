<?php
namespace Drupal\drauta_alters\EventSubscriber;

use Drupal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BlockPages implements EventSubscriberInterface {

  public function checkForRedirection(GetResponseEvent $event) {
  
  	$route_name = \Drupal::routeMatch()->getRouteName();
  	
  	$entrar = FALSE;
  	
  	$vetadas_page = [
	  	'entity.group.add_page' => 'Pagina de anadir un nuevo grupo, aparece el listado de grupos completo'
  	];
  	
  
  	
  	
  	if(array_key_exists($route_name, $vetadas_page)) {
	  	$entrar = TRUE;
  	} else if ($route_name == 'entity.group.add_form') {
	   	$route_param = \Drupal::routeMatch()->getParameter('group_type');
	  	if($route_param != null) {
		  $id_param = $route_param->id();		  
		  $params_vetados = [
			  'closed_group' => 1,
			  'open_group' => 1,
		  ];
		  
		 if(array_key_exists($id_param, $params_vetados)) {
			  $entrar = TRUE;
		  }		  	
	  	}	  	
  	}
  	
  	
  	
  	
  	if($entrar) {
	  	$url = \Drupal::getContainer()->get('url_generator')->generateFromRoute('<front>');
	  	$event->setResponse(new RedirectResponse($url, 301));
  	}
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkForRedirection');

    return $events;
  }
}
?>