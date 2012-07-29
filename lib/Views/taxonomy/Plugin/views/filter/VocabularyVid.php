<?php

/**
 * @file
 * Definition of views_handler_filter_vocabulary_vid.
 */

namespace Views\taxonomy\Plugin\views\filter;

use Drupal\Core\Annotation\Plugin;
use Drupal\views\Plugin\views\filter\InOperator;

/**
 * Filter by vocabulary id.
 *
 * @ingroup views_filter_handlers
 */

/**
 * @Plugin(
 *   plugin_id = "vocabulary_vid"
 * )
 */
class VocabularyVid extends InOperator {
  function get_value_options() {
    if (isset($this->value_options)) {
      return;
    }

    $this->value_options = array();
    $vocabularies = taxonomy_vocabulary_get_names();
    foreach ($vocabularies as $voc) {
      $this->value_options[$voc->vid] = $voc->name;
    }
  }
}