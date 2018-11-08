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
    // Override title of the user page.
    if ($route = $collection->get('entity.user.canonical')) {
      $defaults = $route->getDefaults();
      $defaults['_title_callback'] = 'Drupal\tvshows_config\Classes\TitleResolver::userTitle';
      $route->setDefaults($defaults);
    }
  }

}
