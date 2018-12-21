<?php

namespace Drupal\tvshows_config\Classes;

use Drupal\Component\Utility\Xss;
use Drupal\user\UserInterface;
use Drupal\tvshows_config\Services\ViewHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\tvshows_config\Services\UserHelper;

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
   * The user helper service.
   *
   * @var \Drupal\tvshows_config\Services\UserHelper
   */
  protected $userHelper;

  /**
   * {@inheritdoc}
   */
  public function __construct(ViewHelper $view_helper, UserHelper $user_helper) {
    $this->viewHelper = $view_helper;
    $this->userHelper= $user_helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('tvshows_config.view_helper'),
        $container->get('tvshows_config.user_helper')
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
    return $this->userHelper->getTitle($user);
  }

  /**
   * Route title callback.
   *
   * @return string|array
   *   The view title as a renderable array.
   *   NULL.
   */
  public function viewTitle() {
    return $this->viewHelper->getTitle();
  }

}
