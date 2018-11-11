<?php

namespace Drupal\tvshows_config\Plugin\Field\FieldFormatter;

use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Component\Utility\Unicode;

/**
 * Plugin implementation of the 'social media link' formatter.
 *
 * @FieldFormatter(
 *   id = "social_media_link",
 *   label = @Translation("Social Media Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class SocialMediaLinkFormatter extends LinkFormatter {

  /**
   * Function to get the icon classes.
   *
   * @param object $url
   *   The drupal Url object.
   *
   * @return string
   *   The icon classes.
   */
  public function extractIconFromUrl($url) {
    $domain = $this->getUrlDomain($url);
    return in_array($domain, $this->getKnownWebsites()) ? 'fab fa-' . $domain : 'fa fa-globe';
  }
  
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $entity = $items->getEntity();
    $settings = $this->getSettings();
    $element = parent::viewElements($items, $langcode);

    if (!(!empty($settings['url_only']) && !empty($settings['url_plain']))) {
      foreach ($items as $delta => $item) {
        $url = $this->buildUrl($item);
        $link_title = $url->toString();

        // If the title field value is available, use it for the link text.
        if (empty($settings['url_only']) && !empty($item->title)) {
          // Unsanitized token replacement here because the entire link title
          // gets auto-escaped during link generation in
          // \Drupal\Core\Utility\LinkGenerator::generate().
          $link_title = \Drupal::token()->replace($item->title, [$entity->getEntityTypeId() => $entity], ['clear' => TRUE]);
        }

        // Trim the link text to the desired length.
        if (!empty($settings['trim_length'])) {
          $link_title = Unicode::truncate($link_title, $settings['trim_length'], FALSE, TRUE);
        }

        $element[$delta]['#title'] = $this->getLinkContent($url, $link_title);
      }
    }

    return $element;
  }

  /**
   * Function to get the domain name from an Url.
   *
   * @param object $url
   *   The drupal Url object.
   *
   * @return string
   *   The domain name.
   */
  public function getUrlDomain($url) {
    $uri = $url->getUri();
    $urlobj = parse_url($uri);
    $host_names = explode(".", $urlobj['host']);
    $domain_name= $host_names[count($host_names)-2];
    return $domain_name;
  }

  /**
   * Function that returns the known websites.
   *
   * @return array
   *   The array of the known websites.
   */
  public function getKnownWebsites() {
    return [
      'facebook',
      'twitter',
      'github',
      'instagram',
      'pinterest',
      'steam',
      'firefox',
      'youtube',
      'linkedin',
      'flickr',
      'drupal',
      'tumblr',
    ];
  }

  /**
   * Function to return the icon HTML content.
   * 
   * @param unknown $url
   *   The drupal Url object.
   * @param unknown $title
   *   The title of the link.
   *
   * @return \Drupal\Component\Render\MarkupInterface
   *   The icon HTML content.
   */
  public function getLinkContent($url, $title) {
    $icon = $this->extractIconFromUrl($url);
    $content = '<i class="' . $icon . '" aria-hidden="true"></i>';
    $content .= '<span class="sr-only">' . $title . '</span>';
    return Markup::create($content);
  }

}
