<?php

namespace Drupal\tvshows_config\Services;

use Drupal\user\UserInterface;
use Drupal\Component\Utility\Xss;

/**
 * Class UserHelper.
 */
class UserHelper{

  /**
   * Gets the title of the user.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   *
   * @return string|array
   *   The user account name as a renderable array or an empty string if $user is
   *   NULL.
   */
  public function getTitle(UserInterface $user = NULL) {
    $user_title = ($user_name = $user->field_user_name->value) ? $user->field_user_name->value : $user->getDisplayName();
    return $user ? ['#markup' => $user_title, '#allowed_tags' => Xss::getHtmlTagList()] : '';
  }

}
