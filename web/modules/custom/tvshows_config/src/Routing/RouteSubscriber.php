<?php

namespace Drupal\tvshows_config\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Override title for some routes.
    $routes = [
      'entity.user.canonical' => 'userTitle',
      'view.taxonomy_term.page_1' => 'newsViewsTitle'
    ];
    foreach ($routes as $route_name => $title_function) {
      $route = $collection->get($route_name);
      $this->setRouteTitle($route, $title_function);
    }
  }

  /**
   * Function to override the title of an existing route.
   *
   * @param object $route
   *   The drupal route object.
   * @param string $title_function
   *   The custom function to call to generate the title.
   */
  protected function setRouteTitle($route, $title_function) {
    $defaults = $route->getDefaults();
    $defaults['_title_callback'] = 'Drupal\tvshows_config\Classes\TitleResolver::' . $title_function;
    $route->setDefaults($defaults);
  }

}
