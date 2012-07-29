<?php

/**
 * @file
 * Definition of views_handler_filter_file_status.
 */

namespace Views\file\Plugin\views\filter;

use Drupal\Core\Annotation\Plugin;
use Drupal\views\Plugin\views\filter\InOperator;

/**
 * Filter by file status.
 *
 * @ingroup views_filter_handlers
 */

/**
 * @Plugin(
 *   plugin_id = "file_status"
 * )
 */
class Status extends InOperator {
  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_options = _views_file_status();
    }
  }
}