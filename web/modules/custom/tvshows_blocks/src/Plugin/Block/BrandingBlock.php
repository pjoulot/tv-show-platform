<?php

namespace Drupal\tvshows_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Branding' Block
 *
 * @Block(
 *   id = "tvshow_branding_block",
 *   admin_label = @Translation("TvShow Branding Block"),
 * )
 */
class BrandingBlock extends BlockBase implements BlockPluginInterface {
  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = \Drupal::service('config.factory')->getEditable('system.site');

    return array(
      '#theme' => 'tvshow_branding_block',
      '#site_slogan' => $config->get('slogan', ''),
      '#site_url_name' => $config->get('site_url_name', ''),
    );
  }
}
