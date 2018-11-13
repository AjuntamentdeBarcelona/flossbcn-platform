<?php

namespace Drupal\leaflet_cluster_view\TwigExtension;


/**
 * Class LeafletClusterTwigExtension.
 */
class LeafletClusterTwigExtension extends \Twig_Extension {


   /**
    * {@inheritdoc}
    */
    public function getTokenParsers() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getNodeVisitors() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getFilters() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getTests() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getFunctions() {
      return [
        new \Twig_SimpleFunction('leaflet_views_embed_view', [$this, 'leaflet_views_embed_view']),
        new \Twig_SimpleFunction('leaflet_views_embed_view_custom_query', [$this, 'leaflet_views_embed_view_custom_query']),
      ];
    }

   /**
    * {@inheritdoc}
    */
    public function getOperators() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getName() {
      return 'leaflet_cluster_view.twig.extension';
    }

    public static function leaflet_views_embed_view($view_id = null, $view_display = null, $args = []) {
      return views_embed_view($view_id, $view_display);
    }

    public static function leaflet_views_embed_view_custom_query() {
      // Impresión de los puntoss
      return array(
        '#theme' => 'leaflet_cluster_view_show',
        '#test_var' => '',
      );
    }

}
