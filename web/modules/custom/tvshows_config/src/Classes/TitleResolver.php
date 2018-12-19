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
   *   The user account name as a renderable array or an empty string if $user is
   *   NULL.
   */
  public function userTitle(UserInterface $user = NULL) {
    $user_title = ($user_name = $user->field_user_name->value) ? $user->field_user_name->value : $user->getDisplayName();
    return $user ? ['#markup' => $user_title, '#allowed_tags' => Xss::getHtmlTagList()] : '';
  }

  /**
   * Route title callback.
   *
   * @return string|array
   *   The view title as a renderable array.
   *   NULL.
   */
  public function arg0ViewsTitle() {
    $title = NULL;
    $view = $this->getCurrentViewObject(['arg_0']);
    if (!empty($view)) {
      $title_pattern = $this->getViewTitlePattern($view);
      $parameters = \Drupal::routeMatch()->getParameters();
      $arg_0 = $parameters->get('arg_0');
      // Check if the view is filtering by a bundle.
      $filtered_bundles = $this->getTaxonomyBundles($view);
      $term = $this->getTaxonomyTermByName($arg_0, $filtered_bundles, $this->getTermNameField($view));
      if (!empty($term)) {
        // @todo Use a dependency injection to use the translation service.
        $title = t($title_pattern, array('@term_name' => $term->getName()));
      }
      else {
        $title = $view->getTitle();
      }
    }

    return $title ? ['#markup' => $title, '#allowed_tags' => Xss::getHtmlTagList()] : '';
  }

  /**
   * Function to get the bundles where to extract term names.
   *
   * @param object $view
   *   The view object.
   *
   * @return array
   *   The array containing the vocabularies.
   */
  public function getTaxonomyBundles($view) {
    if (!empty($view->argument['name'])) {
      $bundles = ($bundles = $view->argument['name']->options['validate_options']['bundles']) ? array_keys($bundles) : [];
    }
    else {
      switch ($view->id()) {
        case 'seasons_list':
          $bundles = ['tv_show'];
          break;
      }
    }
    return (isset($bundles)) ? $bundles : [];
  }

  /**
   * Function to know what field to use to search.
   *
   * @param object $view
   *   The view object.
   *
   * @return string
   *   The field name used to search.
   */
  public function getTermNameField($view) {
    $name = '';
    switch ($view->id()) {
      case 'seasons_list':
        $name = 'field_alias_name';
        break;
    }
    return $name;
  }

  /**
   * Function to get the pattern to use for the title page.
   *
   * @param object $view
   *   The view object.
   *
   * @return string
   *   The title pattern.
   */
  public function getViewTitlePattern($view) {
    $title = '';
    switch ($view->id()) {
      case 'taxonomy_term':
        $title = 'News @term_name';
        break;
      case 'seasons_list':
        $title = '@term_name Season List';
        break;
    }
    return $title;
  }

  /**
   * Function to get the current view object.
   *
   * @param array $args
   *   The parameters to set.
   *
   * @return NULL|\Drupal\views\ViewExecutable
   *   The view object.
   */
  public function getCurrentViewObject(array $args = []) {
    $view = NULL;
    $route = \Drupal::routeMatch()->getRouteObject();
    $parameters = \Drupal::routeMatch()->getParameters();
    if ($route) {
      $view_id = $parameters->get('view_id');
      $display_id = $parameters->get('display_id');
      $args_values = [];
      foreach ($args as $arg) {
        $args_values[] = $parameters->get($arg);
      }
      if (!empty($view_id) && !empty($display_id)) {
        $view = Views::getView($view_id);
        if ($view) {
          $view->setArguments($args_values);
          $view->setDisplay($display_id);
          $view->preExecute();
        }
      }
    }
    return $view;
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
  public function getTaxonomyTermByName($name, $vids = [], $field = '') {
    // @todo Move this function inside a separated service.
    $found_term = NULL;

    $query = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->getQuery();
    if (!empty($vids)) {
      $query->condition('vid', $vids, 'IN');
    }
    $tids = $query->execute();
    $terms = Term::loadMultiple($tids);

    foreach ($terms as $term) {
      $name = (!empty($field)) ? $term->{$field}->value : $term->getName();
      $term_name = Html::getId(strtolower($name));
      if ($term_name === $name) {
        $found_term = $term;
        break;
      }
    }

    return $found_term;
  }
}
