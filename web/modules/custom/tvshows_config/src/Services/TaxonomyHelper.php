<?php

namespace Drupal\tvshows_config\Services;

use Drupal\Component\Utility\Html;
use Drupal\taxonomy\Entity\Term;

/**
 * Class TaxonomyHelper.
 */
class TaxonomyHelper {

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
