<?php

namespace Drupal\tvshows_config\Classes;

use Drupal\Component\Utility\Xss;
use Drupal\user\UserInterface;
use Drupal\views\Views;
use Drupal\taxonomy\Entity\Term;
use Drupal\Component\Utility\Html;

/**
 * Class to override route titles.
 */
class TitleResolver {

  /**
   * Route title callback.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   *
   * @return string|array
   *   The user account name as a render array or an empty string if $user is
   *   NULL.
   */
  public function userTitle(UserInterface $user = NULL) {
    $user_title = ($user_name = $user->field_user_name->value) ? $user->field_user_name->value : $user->getDisplayName();
    return $user ? ['#markup' => $user_title, '#allowed_tags' => Xss::getHtmlTagList()] : '';
  }

  /**
   * Route title callback.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   *
   * @return string|array
   *   The user account name as a render array or an empty string if $user is
   *   NULL.
   */
  public function newsViewsTitle() {
    $title = NULL;
    $route = \Drupal::routeMatch()->getRouteObject();
    $parameters = \Drupal::routeMatch()->getParameters();
    if ($route) {
      $view_id = $parameters->get('view_id');
      $display_id = $parameters->get('display_id');
      $arg_0 = $parameters->get('arg_0');
      if (!empty($view_id) && !empty($display_id) && !empty($arg_0)) {
        $view = Views::getView($view_id);
        if ($view) {
          $args = [$arg_0];
          $view->setArguments($args);
          $view->setDisplay($display_id);
          $view->preExecute();

          // Check if the view is filtering by a bundle.
          $filtered_bundles = ($bundles = $view->argument['name']->options['validate_options']['bundles']) ? array_keys($bundles) : [];
          $term = $this->getTaxonomyTermByName($arg_0, $filtered_bundles);
          if (!empty($term)) {
            // @todo Use a dependency injection to use the translation service.
            $title = t('News @term_name', array('@term_name' => $term->getName()));
          }
          else {
            $title = $view->getTitle();
          }
        }
      }
    }
    return $title ? ['#markup' => $title, '#allowed_tags' => Xss::getHtmlTagList()] : '';
  }

  /**
   * Function to get term by name.
   *
   * @param string $name
   * The searched name.
   * @param array $vids
   *   The vocabularies to search.
   *
   * @return NULL|object
   *   The found term object or NULL if nothing is found.
   */
  public function getTaxonomyTermByName($name, $vids = []) {
    // @todo Move this function inside a separated service.
    $found_term = NULL;

    $query = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->getQuery();
    if (!empty($vids)) {
      $query->condition('vid', $vids, 'IN');
    }
    $tids = $query->execute();
    $terms = Term::loadMultiple($tids);

    foreach ($terms as $term) {
      $term_name = Html::getId(strtolower($term->getName()));
      if ($term_name === $name) {
        $found_term = $term;
        break;
      }
    }

    return $found_term;
  }
}
