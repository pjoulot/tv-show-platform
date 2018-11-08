<?php

namespace Drupal\tvshows_config\Classes;

use Drupal\Component\Utility\Xss;
use Drupal\user\UserInterface;

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

}
