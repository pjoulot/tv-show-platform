<?php

namespace Drupal\tvshows_config\Services;

use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class SeasonHelper.
 */
class SeasonHelper {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Get the years range of the season.
   * 
   * @param \Drupal\taxonomy\Entity\Term $term
   *   The season term.
   *   
   * @return string
   *   The years range of the season.
   */
  public function getYearsRange(Term $season) {
    $query = $this->entityTypeManager->getStorage('node')->getQuery();
    $nids = $query->condition('type', 'episode')
    ->condition('field_season', $season->id())
      ->condition('status', '1')
      ->exists('field_original_air_date')
      ->sort('field_original_air_date', 'ASC')
      ->execute();

    // Filter to only load the oldest and the newest episodes.
    if (count($nids) > 2) {
      $first = reset($nids);
      $last = end($nids);
      $nids = [$first, $last];
    }

    $episodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);

    $last_year = NULL;
    $years_range = '';
    foreach ($episodes as $episode) {
      $year = date('Y', strtotime($episode->field_original_air_date->value));
      if ($year!== $last_year) {
        $years_range .= (!empty($years_range) ? '-' : '') . $year;
        $last_year = $year;
      }
    }
    return $years_range;
  }

  /**
   * Get the link of the season page.
   *
   * @param \Drupal\taxonomy\Entity\Term $term
   *   The season term.
   *
   * @return string
   *   The url of the season page.
   */
  public function getSeasonPageUrl(Term $season) {
    $show_name = ($show = $season->field_season_tv_show->entity) ? $show->field_alias_name->value : '';
    $season_number = $season->field_number->value;
    $url = '/serie/' . $show_name. '/saison/' . $season_number;
    return $url;
  }
}
