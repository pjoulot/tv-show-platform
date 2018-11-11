<?php

namespace Drupal\tvshows_config\Plugin\Field\FieldFormatter;

use Drupal\datetime\Plugin\Field\FieldFormatter\DateTimeDefaultFormatter;

/**
 * Plugin implementation of the 'timestamp' formatter.
 *
 * @FieldFormatter(
 *   id = "info_datetime",
 *   label = @Translation("Info Date"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class InfoTimestampFormatter extends DateTimeDefaultFormatter{

}
