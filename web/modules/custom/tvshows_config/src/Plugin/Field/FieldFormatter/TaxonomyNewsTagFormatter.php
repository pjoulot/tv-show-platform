<?php

namespace Drupal\tvshows_config\Plugin\Field\FieldFormatter;

use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Exception\UndefinedLinkTemplateException;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Component\Utility\Html;


/**
 * Plugin implementation of the 'news tag link' formatter.
 *
 * @FieldFormatter(
 *   id = "news_tag_link",
 *   label = @Translation("News tag Link"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class TaxonomyNewsTagFormatter extends EntityReferenceLabelFormatter {

    /**
     * {@inheritdoc}
     */
    public function viewElements(FieldItemListInterface $items, $langcode) {
      $elements = [];
      $output_as_link = $this->getSetting('link');
      
      foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
        $label = $entity->label();
        // If the link is to be displayed and the entity has a uri, display a
        // link.
        if ($output_as_link && !$entity->isNew()) {
          try {
            $uri = $entity->urlInfo();

            // @todo Create a service in order to get the term name URI compliant.
            $term_name = Html::getId(strtolower($entity->getName()));
            $uri= Url::fromRoute('view.taxonomy_term.page_1');
            $uri->setRouteParameter('arg_0', $term_name);
          }
          catch (UndefinedLinkTemplateException $e) {
            // This exception is thrown by \Drupal\Core\Entity\Entity::urlInfo()
            // and it means that the entity type doesn't have a link template nor
            // a valid "uri_callback", so don't bother trying to output a link for
            // the rest of the referenced entities.
            $output_as_link = FALSE;
          }
        }

        if ($output_as_link && isset($uri) && !$entity->isNew()) {
          $elements[$delta] = [
              '#type' => 'link',
              '#title' => $label,
              '#url' => $uri,
              '#options' => $uri->getOptions(),
          ];
          
          if (!empty($items[$delta]->_attributes)) {
            $elements[$delta]['#options'] += ['attributes' => []];
            $elements[$delta]['#options']['attributes'] += $items[$delta]->_attributes;
            // Unset field item attributes since they have been included in the
            // formatter output and shouldn't be rendered in the field template.
            unset($items[$delta]->_attributes);
          }
        }
        else {
          $elements[$delta] = ['#plain_text' => $label];
        }
        $elements[$delta]['#cache']['tags'] = $entity->getCacheTags();
      }
      
      return $elements;
    }

  }
  