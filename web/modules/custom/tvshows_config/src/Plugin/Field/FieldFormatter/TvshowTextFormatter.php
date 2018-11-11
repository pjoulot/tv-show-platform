<?php

namespace Drupal\tvshows_config\Plugin\Field\FieldFormatter;

use Drupal\text\Plugin\Field\FieldFormatter\TextDefaultFormatter;

/**
 * Plugin implementation of the 'text_default' formatter.
 *
 * @FieldFormatter(
 *   id = "tv_show_text",
 *   label = @Translation("Tvshow text"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class TvshowTextFormatter extends TextDefaultFormatter{

}
