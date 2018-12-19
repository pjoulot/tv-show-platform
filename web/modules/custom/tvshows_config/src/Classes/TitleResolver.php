<?php

namespace Drupal\tvshows_config\Classes;

use Drupal\Component\Utility\Xss;
use Drupal\user\UserInterface;
use Drupal\tvshows_config\Services\ViewHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * Class to override route titles.
 */
class TitleResolver implements ContainerInjectionInterface {

  /**
   * The taxonomy helper service.
   *
   * @var \Drupal\tvshows_config\Services\ViewHelper
   */
  protected $viewHelper;

  /**
   * {@inheritdoc}
   */
  public function __construct(ViewHelper $view_helper) {
    $this->viewHelper = $view_helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tvshows_config.view_helper')
    );
  }

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
    return $this->viewHelper->getViewTitle();
  }

}
