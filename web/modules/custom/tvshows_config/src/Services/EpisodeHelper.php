<?php

namespace Drupal\tvshows_config\Services;

use Drupal\node\Entity\Node;

/**
 * Class EpisodeHelper.
 */
class EpisodeHelper {

  /**
   * Get the season episode number of an episode.
   *
   * @param \Drupal\node\Entity\Node $episode
   *   The episode node.
   *
   * @return string
   *   The season episode number.
   */
  public function getSeasonEpisodeNumber(Node $episode) {
    $number = ($season = $episode->field_season->entity) ? $season->field_number->value : '';
    $episode_number = $episode->field_episode_number->value;
    $number .= ($episode_number < 10) ? '0' . $episode_number : $episode_number;
    return $number;
  }
}
